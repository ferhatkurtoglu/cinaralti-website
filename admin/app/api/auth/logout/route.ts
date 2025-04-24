import { NextResponse } from 'next/server';

export async function POST() {
  try {
    console.log('Çıkış yapılıyor');
    
    // Response oluştur
    const response = NextResponse.json({
      success: true,
      message: 'Başarıyla çıkış yapıldı'
    });
    
    // Cookie'yi sıfırla
    response.cookies.set({
      name: 'adminToken',
      value: '',
      httpOnly: true,
      path: '/',
      expires: new Date(0), // Hemen sona erdir
      sameSite: 'lax'
    });
    
    return response;
  } catch (error) {
    console.error('Çıkış yapılırken hata oluştu:', error);
    return NextResponse.json(
      { error: 'Çıkış yapılırken bir hata oluştu' },
      { status: 500 }
    );
  }
} 