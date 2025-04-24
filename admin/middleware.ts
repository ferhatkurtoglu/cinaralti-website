import * as jose from 'jose';
import type { NextRequest } from 'next/server';
import { NextResponse } from 'next/server';

// Basit ve sadece dashboard koruması yapan bir middleware
export async function middleware(request: NextRequest) {
  const path = request.nextUrl.pathname;
  
  // Sadece dashboard ile başlayan URL'leri koru
  if (path.startsWith('/dashboard')) {
    // Cookie'den token al
    const token = request.cookies.get('adminToken')?.value;
    
    // Token yoksa doğrudan giriş sayfasına yönlendir
    if (!token) {
      return NextResponse.redirect(new URL('/', request.url));
    }
    
    try {
      // JWT secret
      const secret = new TextEncoder().encode(
        process.env.JWT_SECRET || 'fallback_jwt_secret'
      );
      
      // Token doğrulama
      const { payload } = await jose.jwtVerify(token, secret);
      
      return NextResponse.next();
    } catch (error) {
      return NextResponse.redirect(new URL('/', request.url));
    }
  }
  
  return NextResponse.next();
}

// Sadece '/dashboard' ile başlayan URL'ler için middleware çalışır
export const config = {
  matcher: ['/dashboard/:path*']
}; 