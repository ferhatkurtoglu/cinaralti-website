import { createDonationType, getDonationTypes } from '@/lib/db';
import { NextResponse } from 'next/server';

export async function GET() {
  try {
    const donationOptions = await getDonationTypes();
    return NextResponse.json(donationOptions);
  } catch (error) {
    console.error('Bağış seçenekleri alınırken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış seçenekleri alınırken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function POST(request: Request) {
  try {
    const data = await request.json();
    
    // Gerekli alanları kontrol et
    if (!data.title && !data.name) {
      return NextResponse.json(
        { error: 'Başlık alanı zorunludur' },
        { status: 400 }
      );
    }
    
    // Name alanı yoksa title alanını kullan
    if (!data.name && data.title) {
      data.name = data.title;
    }
    
    // Slug yoksa, isimden oluştur
    if (!data.slug) {
      data.slug = (data.title || data.name).toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    }
    
    // is_active field mapping
    if (data.is_active !== undefined) {
      data.active = data.is_active;
    }
    
    const result = await createDonationType(data);
    
    return NextResponse.json(result, { status: 201 });
  } catch (error) {
    console.error('Bağış seçeneği eklenirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış seçeneği eklenirken bir hata oluştu' },
      { status: 500 }
    );
  }
}
