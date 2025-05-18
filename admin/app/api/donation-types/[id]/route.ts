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
        { error: 'Geçersiz bağış seçeneği ID' },
        { status: 400 }
      );
    }
    
    const donationOption = await getDonationTypeById(id);
    
    if (!donationOption) {
      return NextResponse.json(
        { error: 'Bağış seçeneği bulunamadı' },
        { status: 404 }
      );
    }
    
    return NextResponse.json(donationOption);
  } catch (error) {
    console.error('Bağış seçeneği alınırken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış seçeneği alınırken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function PUT(request: Request, { params }: Params) {
  try {
    const id = parseInt(params.id);
    
    if (isNaN(id)) {
      return NextResponse.json(
        { error: 'Geçersiz bağış seçeneği ID' },
        { status: 400 }
      );
    }
    
    const data = await request.json();
    
    // Bağış seçeneğinin var olduğunu kontrol et
    const existingDonationOption = await getDonationTypeById(id);
    
    if (!existingDonationOption) {
      return NextResponse.json(
        { error: 'Bağış seçeneği bulunamadı' },
        { status: 404 }
      );
    }
    
    const result = await updateDonationType(id, data);
    
    return NextResponse.json(result);
  } catch (error) {
    console.error('Bağış seçeneği güncellenirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış seçeneği güncellenirken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const id = parseInt(params.id);
    
    if (isNaN(id)) {
      return NextResponse.json(
        { error: 'Geçersiz bağış seçeneği ID' },
        { status: 400 }
      );
    }
    
    // Bağış seçeneğinin var olduğunu kontrol et
    const existingDonationOption = await getDonationTypeById(id);
    
    if (!existingDonationOption) {
      return NextResponse.json(
        { error: 'Bağış seçeneği bulunamadı' },
        { status: 404 }
      );
    }
    
    await deleteDonationType(id);
    
    return NextResponse.json({ success: true });
  } catch (error) {
    console.error('Bağış seçeneği silinirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış seçeneği silinirken bir hata oluştu' },
      { status: 500 }
    );
  }
} 