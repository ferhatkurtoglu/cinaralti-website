import { getServerSession } from 'next-auth'
import { NextResponse } from 'next/server'
import { authOptions } from '../auth/[...nextauth]/route'

export async function GET(request: Request) {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    // URL'den type parametresini al
    const { searchParams } = new URL(request.url)
    const type = searchParams.get('type') || 'blog'

    // PHP backend'e istek gönder - type parametresi ile
    const response = await fetch(`http://localhost/cinaralti-website/database/api/categories.php?type=${type}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })

    if (!response.ok) {
      throw new Error('Kategoriler getirilemedi')
    }

    const data = await response.json()
    
    // Yanıtı Next.js formatına dönüştür
    const categories = data.data.map((category: any) => ({
      id: category.id,
      name: category.name,
      slug: category.slug,
      description: category.description,
      type: category.type,
      createdAt: category.created_at,
      updatedAt: category.updated_at,
    }))

    return NextResponse.json(categories)
  } catch (error) {
    console.error('Kategoriler getirilirken hata oluştu:', error)
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
    const { name, slug, description, type } = body

    if (!name) {
      return new NextResponse('Missing required fields', { status: 400 })
    }

    // PHP backend'e istek gönder - XAMPP üzerinden doğru adres
    const response = await fetch('http://localhost/cinaralti-website/database/api/categories.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name,
        slug,
        description,
        type: type || 'blog'
      }),
    })

    if (!response.ok) {
      const errorData = await response.json()
      return new NextResponse(errorData.error || 'Kategori oluşturulamadı', { status: response.status })
    }

    const data = await response.json()
    
    // Oluşturulan kategoriyi getir
    const createdCategory = await fetch(`http://localhost/cinaralti-website/database/api/categories.php?id=${data.data.id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })

    if (createdCategory.ok) {
      const categoryData = await createdCategory.json()
      return NextResponse.json(categoryData.data)
    }

    return NextResponse.json({ id: data.data.id, name })
  } catch (error) {
    console.error('Kategori oluşturulurken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 