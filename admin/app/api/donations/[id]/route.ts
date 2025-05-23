import { executeQuery } from '@/lib/db';
import { NextResponse } from 'next/server';

export async function GET(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const donationId = params.id;
    
    if (!donationId) {
      return NextResponse.json(
        { error: 'Bağış ID parametre eksik' },
        { status: 400 }
      );
    }
    
    // Bağış detaylarını getir
    const query = `
      SELECT * FROM donations_made 
      WHERE id = ? 
      LIMIT 1
    `;
    
    const donation = await executeQuery({ query, values: [donationId] });
    
    if (!donation || donation.length === 0) {
      return NextResponse.json(
        { error: 'Bağış bulunamadı' },
        { status: 404 }
      );
    }
    
    return NextResponse.json(donation[0]);
    
  } catch (error) {
    console.error('Bağış detayları getirilirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış detayları getirilirken bir hata oluştu.' },
      { status: 500 }
    );
  }
}

export async function PATCH(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const donationId = params.id;
    
    if (!donationId) {
      return NextResponse.json(
        { error: 'Bağış ID parametre eksik' },
        { status: 400 }
      );
    }
    
    // İstek gövdesinden verileri al
    const body = await request.json();
    const { payment_status } = body;
    
    if (!payment_status) {
      return NextResponse.json(
        { error: 'Durum bilgisi eksik' },
        { status: 400 }
      );
    }
    
    // Geçerli durum değerleri kontrolü
    const validStatuses = ['pending', 'completed', 'failed'];
    if (!validStatuses.includes(payment_status)) {
      return NextResponse.json(
        { error: 'Geçersiz durum değeri' },
        { status: 400 }
      );
    }
    
    // Bağışın var olup olmadığını kontrol et
    const checkQuery = `SELECT id FROM donations_made WHERE id = ? LIMIT 1`;
    const existingDonation = await executeQuery({ query: checkQuery, values: [donationId] });
    
    if (!existingDonation || existingDonation.length === 0) {
      return NextResponse.json(
        { error: 'Bağış bulunamadı' },
        { status: 404 }
      );
    }
    
    // Durumu güncelle
    const updateQuery = `
      UPDATE donations_made 
      SET payment_status = ?
      WHERE id = ?
    `;
    
    await executeQuery({ query: updateQuery, values: [payment_status, donationId] });
    
    // Güncellenmiş bağış verilerini getir
    const getQuery = `
      SELECT * FROM donations_made 
      WHERE id = ? 
      LIMIT 1
    `;
    
    const updatedDonation = await executeQuery({ query: getQuery, values: [donationId] });
    
    return NextResponse.json(updatedDonation[0]);
    
  } catch (error) {
    console.error('Bağış durumu güncellenirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağış durumu güncellenirken bir hata oluştu.' },
      { status: 500 }
    );
  }
} 