import { getServerSession } from 'next-auth'
import { NextResponse } from 'next/server'
import { authOptions } from '../auth/[...nextauth]/route'

export async function GET() {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    // PHP backend'e istek gönder - XAMPP üzerinden doğru adres
    const response = await fetch('http://localhost/cinaralti-website/database/api/videos.php', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })

    if (!response.ok) {
      throw new Error('Videolar getirilemedi')
    }

    const videos = await response.json()
    
    return NextResponse.json(videos)
  } catch (error) {
    console.error('Videolar getirilirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
}

export async function POST(request: Request) {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    const body = await request.json()
    const { title, description, url, thumbnail, status, featured, categoryId, tags } = body

    if (!title || !url || !status) {
      return new NextResponse('Missing required fields', { status: 400 })
    }

    // PHP backend'e video oluşturma isteği gönder - XAMPP üzerinden doğru adres
    const response = await fetch('http://localhost/cinaralti-website/database/api/videos.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        title,
        description: description || null,
        url,
        thumbnail: thumbnail || null,
        status,
        featured: featured || false,
        categoryId: categoryId || null,
        tags: tags || null,
        authorId: 1, // Şimdilik default author
      }),
    })

    if (!response.ok) {
      const error = await response.json()
      return NextResponse.json({ error: error.error || 'Video oluşturulamadı' }, { status: 400 })
    }

    const data = await response.json()
    
    return NextResponse.json(data)
  } catch (error) {
    console.error('Video oluşturulurken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 