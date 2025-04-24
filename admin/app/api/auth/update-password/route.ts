import { executeQuery } from '@/lib/db';
import bcrypt from 'bcryptjs';
import { NextResponse } from 'next/server';

// Şifre güncelleme
export async function POST(request: Request) {
  try {
    const { token, newPassword } = await request.json();
    
    if (!token || !newPassword) {
      return NextResponse.json(
        { error: 'Token ve yeni şifre zorunludur' },
        { status: 400 }
      );
    }
    
    // Token ile kullanıcıyı bul
    const query = `
      SELECT * FROM users 
      WHERE reset_token = ? AND reset_token_expiry > NOW()
      LIMIT 1
    `;
    
    const results = await executeQuery({ query, values: [token] });
    
    if (!results || results.length === 0) {
      return NextResponse.json(
        { error: 'Geçersiz veya süresi dolmuş token' },
        { status: 400 }
      );
    }
    
    const user = results[0];
    
    // Yeni şifreyi hashle
    const hashedPassword = await bcrypt.hash(newPassword, 10);
    
    // Şifreyi güncelle ve token bilgilerini sıfırla
    const updateQuery = `
      UPDATE users
      SET password = ?, reset_token = NULL, reset_token_expiry = NULL
      WHERE id = ?
    `;
    
    await executeQuery({
      query: updateQuery,
      values: [hashedPassword, user.id]
    });
    
    return NextResponse.json({
      success: true,
      message: 'Şifreniz başarıyla güncellendi'
    });
    
  } catch (error) {
    console.error('Şifre güncellenirken hata:', error);
    return NextResponse.json(
      { error: 'İşlem sırasında bir hata oluştu' },
      { status: 500 }
    );
  }
} 