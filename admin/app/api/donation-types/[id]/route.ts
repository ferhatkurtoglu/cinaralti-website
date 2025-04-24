import { deleteDonationType, getDonationTypeById, updateDonationType } from '@/lib/db';
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
        { error: 'Geçersiz bağış türü ID' },
        { status: 400 }
      );
    }
    
    const donationType = await getDonationTypeById(id);
    
    if (!donationType) {
      return NextResponse.json(
        { error: 'Bağış türü bulunamadı' },
        { status: 404 }
      );
    }
    
    return NextResponse.json(donationType);
  } catch (error) {
    console.error('Bağış türü alınırken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış türü alınırken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function PUT(request: Request, { params }: Params) {
  try {
    const id = parseInt(params.id);
    
    if (isNaN(id)) {
      return NextResponse.json(
        { error: 'Geçersiz bağış türü ID' },
        { status: 400 }
      );
    }
    
    const data = await request.json();
    
    // Bağış türünün var olduğunu kontrol et
    const existingDonationType = await getDonationTypeById(id);
    
    if (!existingDonationType) {
      return NextResponse.json(
        { error: 'Bağış türü bulunamadı' },
        { status: 404 }
      );
    }
    
    const result = await updateDonationType(id, data);
    
    return NextResponse.json(result);
  } catch (error) {
    console.error('Bağış türü güncellenirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış türü güncellenirken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const id = parseInt(params.id);
    
    if (isNaN(id)) {
      return NextResponse.json(
        { error: 'Geçersiz bağış türü ID' },
        { status: 400 }
      );
    }
    
    // Bağış türünün var olduğunu kontrol et
    const existingDonationType = await getDonationTypeById(id);
    
    if (!existingDonationType) {
      return NextResponse.json(
        { error: 'Bağış türü bulunamadı' },
        { status: 404 }
      );
    }
    
    await deleteDonationType(id);
    
    return NextResponse.json({ success: true });
  } catch (error) {
    console.error('Bağış türü silinirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış türü silinirken bir hata oluştu' },
      { status: 500 }
    );
  }
} 