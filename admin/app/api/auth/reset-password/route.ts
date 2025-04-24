import { executeQuery } from '@/lib/db';
import crypto from 'crypto';
import { NextResponse } from 'next/server';
import nodemailer from 'nodemailer';

// Şifre sıfırlama isteği oluştur
export async function POST(request: Request) {
  try {
    const { email } = await request.json();
    
    if (!email) {
      return NextResponse.json(
        { error: 'E-posta adresi zorunludur' },
        { status: 400 }
      );
    }
    
    // Kullanıcıyı veritabanında ara
    const query = `
      SELECT * FROM users 
      WHERE email = ? AND status = 'active'
      LIMIT 1
    `;
    
    const results = await executeQuery({ query, values: [email] });
    
    // Kullanıcı bulunamadı durumunda bile başarılı mesajı dön (güvenlik için)
    if (!results || results.length === 0) {
      return NextResponse.json({
        success: true,
        message: 'Şifre sıfırlama talimatları e-posta adresinize gönderildi'
      });
    }
    
    const user = results[0];
    
    // Şifre sıfırlama token'ı oluştur
    const resetToken = crypto.randomBytes(20).toString('hex');
    const resetTokenExpiry = new Date(Date.now() + 3600000); // 1 saat geçerli
    
    // Token'ı veritabanına kaydet
    const updateQuery = `
      UPDATE users
      SET reset_token = ?, reset_token_expiry = ?
      WHERE id = ?
    `;
    
    await executeQuery({
      query: updateQuery,
      values: [resetToken, resetTokenExpiry, user.id]
    });
    
    // E-posta gönderme işlemi
    const transporter = nodemailer.createTransport({
      host: process.env.SMTP_HOST,
      port: parseInt(process.env.SMTP_PORT || '587'),
      secure: process.env.SMTP_SECURE === 'true',
      auth: {
        user: process.env.SMTP_USER,
        pass: process.env.SMTP_PASSWORD
      }
    });
    
    const resetUrl = `${process.env.NEXT_PUBLIC_BASE_URL}/reset-password?token=${resetToken}`;
    
    await transporter.sendMail({
      from: process.env.SMTP_FROM,
      to: email,
      subject: 'Çınaraltı Admin - Şifre Sıfırlama',
      html: `
        <p>Merhaba,</p>
        <p>Şifrenizi sıfırlamak için aşağıdaki bağlantıya tıklayın:</p>
        <p><a href="${resetUrl}">Şifremi Sıfırla</a></p>
        <p>Bu bağlantı 1 saat süreyle geçerlidir.</p>
        <p>Eğer bu isteği siz yapmadıysanız, lütfen bu e-postayı dikkate almayın.</p>
        <p>Saygılarımızla,<br>Çınaraltı Ekibi</p>
      `
    });
    
    return NextResponse.json({
      success: true,
      message: 'Şifre sıfırlama talimatları e-posta adresinize gönderildi'
    });
    
  } catch (error) {
    console.error('Şifre sıfırlama isteği oluşturulurken hata:', error);
    return NextResponse.json(
      { error: 'İşlem sırasında bir hata oluştu' },
      { status: 500 }
    );
  }
} 