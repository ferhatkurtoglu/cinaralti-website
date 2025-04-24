import { executeQuery } from '@/lib/db';
import bcrypt from 'bcryptjs';
import * as jose from 'jose';
import { NextResponse } from 'next/server';

export async function POST(request: Request) {
  try {
    const { email, password } = await request.json();
    console.log('Giriş denemesi:', email);
    
    // E-posta ve şifre kontrolü
    if (!email || !password) {
      return NextResponse.json(
        { error: 'E-posta ve şifre zorunludur' },
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
    
    // Kullanıcı bulunamadı
    if (!results || results.length === 0) {
      console.log('Kullanıcı bulunamadı:', email);
      return NextResponse.json(
        { error: 'Geçersiz kimlik bilgileri' },
        { status: 401 }
      );
    }
    
    const user = results[0];
    
    // Şifre doğrulama
    const isPasswordValid = await bcrypt.compare(password, user.password);
    
    if (!isPasswordValid) {
      console.log('Şifre geçersiz:', email);
      return NextResponse.json(
        { error: 'Geçersiz kimlik bilgileri' },
        { status: 401 }
      );
    }
    
    console.log('Giriş başarılı:', email);
    
    // JWT token payload
    const payload = { 
      id: user.id,
      email: user.email,
      name: user.name,
      role: user.role
    };
    
    // JWT secret key
    const secret = new TextEncoder().encode(
      process.env.JWT_SECRET || 'fallback_jwt_secret'
    );
    
    // JWT token oluştur (24 saat geçerli)
    const token = await new jose.SignJWT(payload)
      .setProtectedHeader({ alg: 'HS256' })
      .setIssuedAt()
      .setExpirationTime('24h')
      .sign(secret);
    
    // Kullanıcı bilgilerini döndür (şifre hariç)
    const { password: _, ...userInfo } = user;
    
    // Response'u oluştur
    const response = NextResponse.json({
      user: userInfo,
      token
    });
    
    // HTTP-only cookie ekle (middleware için)
    response.cookies.set({
      name: 'adminToken',
      value: token,
      httpOnly: true,
      path: '/',
      maxAge: 60 * 60 * 24, // 1 gün
      sameSite: 'lax'
    });
    
    console.log('Auth API - Cookie ayarlandı, token:', token.substring(0, 20) + '...');
    
    return response;
    
  } catch (error) {
    console.error('Giriş yapılırken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Giriş yapılırken bir hata oluştu' },
      { status: 500 }
    );
  }
} 