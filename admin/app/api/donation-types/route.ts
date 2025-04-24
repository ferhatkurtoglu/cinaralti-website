import { createDonationType, getDonationTypes } from '@/lib/db';
import { NextResponse } from 'next/server';

export async function GET() {
  try {
    const donationTypes = await getDonationTypes();
    return NextResponse.json(donationTypes);
  } catch (error) {
    console.error('Bağış türleri alınırken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış türleri alınırken bir hata oluştu' },
      { status: 500 }
    );
  }
}

export async function POST(request: Request) {
  try {
    const data = await request.json();
    
    // Gerekli alanları kontrol et
    if (!data.name) {
      return NextResponse.json(
        { error: 'İsim alanı zorunludur' },
        { status: 400 }
      );
    }
    
    // Slug yoksa, isimden oluştur
    if (!data.slug) {
      data.slug = data.name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    }
    
    const result = await createDonationType(data);
    
    return NextResponse.json(result, { status: 201 });
  } catch (error) {
    console.error('Bağış türü eklenirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış türü eklenirken bir hata oluştu' },
      { status: 500 }
    );
  }
} 