import { deleteUser, getUserById, updateUser } from '@/lib/db';
import bcrypt from 'bcryptjs';
import { NextResponse } from 'next/server';

interface Params {
  params: {
    id: string;
  };
}

export async function GET(request: Request, { params }: Params) {
  try {
    const id = parseInt(params.id);
    
    if (isNaN(id)) {
      return NextResponse.json(
        { error: 'Geçersiz kullanıcı ID' },
        { status: 400 }
      );
    }
    
    const user = await getUserById(id);
    
    if (!user) {
      return NextResponse.json(
        { error: 'Kullanıcı bulunamadı' },
        { status: 404 }
      );
    }
    
    // Şifreyi API yanıtından çıkar
    const { password, ...userWithoutPassword } = user;
    
    return NextResponse.json(userWithoutPassword);
  } catch (error) {
    console.error('Kullanıcı alınırken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Kullanıcı alınırken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function PUT(request: Request, { params }: Params) {
  try {
    const id = parseInt(params.id);
    
    if (isNaN(id)) {
      return NextResponse.json(
        { error: 'Geçersiz kullanıcı ID' },
        { status: 400 }
      );
    }
    
    const data = await request.json();
    
    // Kullanıcının var olduğunu kontrol et
    const existingUser = await getUserById(id);
    
    if (!existingUser) {
      return NextResponse.json(
        { error: 'Kullanıcı bulunamadı' },
        { status: 404 }
      );
    }
    
    // Şifre değiştirilecekse, hashle
    if (data.password) {
      data.password = await bcrypt.hash(data.password, 10);
    }
    
    await updateUser(id, data);
    
    return NextResponse.json({ id, ...data });
  } catch (error) {
    console.error('Kullanıcı güncellenirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Kullanıcı güncellenirken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const id = parseInt(params.id);
    
    if (isNaN(id)) {
      return NextResponse.json(
        { error: 'Geçersiz kullanıcı ID' },
        { status: 400 }
      );
    }
    
    // Kullanıcının var olduğunu kontrol et
    const existingUser = await getUserById(id);
    
    if (!existingUser) {
      return NextResponse.json(
        { error: 'Kullanıcı bulunamadı' },
        { status: 404 }
      );
    }
    
    await deleteUser(id);
    
    return NextResponse.json({ success: true });
  } catch (error) {
    console.error('Kullanıcı silinirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Kullanıcı silinirken bir hata oluştu' },
      { status: 500 }
    );
  }
} 