import { createUser, getUsers } from '@/lib/db';
import bcrypt from 'bcryptjs';
import { NextResponse } from 'next/server';

export async function GET() {
  try {
    const users = await getUsers();
    return NextResponse.json(users);
  } catch (error) {
    console.error('Kullanıcılar alınırken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Kullanıcılar alınırken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function POST(request: Request) {
  try {
    const data = await request.json();
    
    // Gerekli alanları kontrol et
    if (!data.name || !data.email || !data.password) {
      return NextResponse.json(
        { error: 'Ad, e-posta ve şifre alanları zorunludur' },
        { status: 400 }
      );
    }
    
    // Şifreyi hashleme
    const hashedPassword = await bcrypt.hash(data.password, 10);
    
    // Kullanıcıyı veritabanına ekle
    const userData = {
      ...data,
      password: hashedPassword
    };
    
    const result = await createUser(userData);
    
    return NextResponse.json({ id: result.insertId, ...data }, { status: 201 });
  } catch (error) {
    console.error('Kullanıcı eklenirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Kullanıcı eklenirken bir hata oluştu' },
      { status: 500 }
    );
  }
} 