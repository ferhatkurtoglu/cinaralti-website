import { executeQuery } from '@/lib/db';
import { NextResponse } from 'next/server';

export async function GET(request: Request) {
  try {
    const { searchParams } = new URL(request.url);
    const page = parseInt(searchParams.get('page') || '1');
    const limit = parseInt(searchParams.get('limit') || '20');
    const offset = (page - 1) * limit;
    
    // Filtreleme parametreleri
    const status = searchParams.get('status');
    const type = searchParams.get('type');
    const startDate = searchParams.get('startDate');
    const endDate = searchParams.get('endDate');
    const search = searchParams.get('search');
    
    // Temel sorgu
    let query = `
      SELECT * FROM donations_made WHERE 1=1
    `;
    
    // Toplam kayıt sayısı için sorgu
    let countQuery = `
      SELECT COUNT(*) as total FROM donations_made WHERE 1=1
    `;
    
    const values: any[] = [];
    const countValues: any[] = [];
    
    // Filtreleri uygula
    if (status) {
      query += ` AND payment_status = ?`;
      countQuery += ` AND payment_status = ?`;
      values.push(status);
      countValues.push(status);
    }
    
    if (type) {
      query += ` AND donation_category = ?`;
      countQuery += ` AND donation_category = ?`;
      values.push(type);
      countValues.push(type);
    }
    
    if (startDate) {
      query += ` AND DATE(created_at) >= ?`;
      countQuery += ` AND DATE(created_at) >= ?`;
      values.push(startDate);
      countValues.push(startDate);
    }
    
    if (endDate) {
      query += ` AND DATE(created_at) <= ?`;
      countQuery += ` AND DATE(created_at) <= ?`;
      values.push(endDate);
      countValues.push(endDate);
    }
    
    if (search) {
      query += ` AND (
        donor_name LIKE ? OR 
        donor_email LIKE ? OR 
        donor_phone LIKE ? OR
        donation_category LIKE ?
      )`;
      
      countQuery += ` AND (
        donor_name LIKE ? OR 
        donor_email LIKE ? OR 
        donor_phone LIKE ? OR
        donation_category LIKE ?
      )`;
      
      const searchPattern = `%${search}%`;
      values.push(searchPattern, searchPattern, searchPattern, searchPattern);
      countValues.push(searchPattern, searchPattern, searchPattern, searchPattern);
    }
    
    // Sıralama ve sayfalama ekle
    query += ` ORDER BY created_at DESC LIMIT ? OFFSET ?`;
    values.push(limit, offset);
    
    // Toplam kayıt sayısını al
    const totalResults = await executeQuery({ query: countQuery, values: countValues });
    const totalRecords = totalResults[0].total;
    const totalPages = Math.ceil(totalRecords / limit);
    
    // Ana sorguyu çalıştır
    const donations = await executeQuery({ query, values });
    
    // Bağış türlerini al (filtre için)
    const donationTypesQuery = `
      SELECT DISTINCT donation_category as donation_type FROM donations_made ORDER BY donation_category
    `;
    const donationTypes = await executeQuery({ query: donationTypesQuery });
    
    return NextResponse.json({
      donations,
      pagination: {
        currentPage: page,
        totalPages,
        totalRecords,
        limit
      },
      filters: {
        donationTypes
      }
    });
    
  } catch (error) {
    console.error('Bağışlar getirilirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Bağışlar getirilirken bir hata oluştu.' },
      { status: 500 }
    );
  }
} 