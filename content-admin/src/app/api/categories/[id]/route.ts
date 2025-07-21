import { getServerSession } from 'next-auth'
import { NextResponse } from 'next/server'
import { authOptions } from '../../auth/[...nextauth]/route'

export async function GET(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    // PHP backend'e istek gönder - XAMPP üzerinden doğru adres
    const response = await fetch(`http://localhost/cinaralti-website/database/api/categories.php?id=${params.id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })

    if (!response.ok) {
      if (response.status === 404) {
        return new NextResponse('Not Found', { status: 404 })
      }
      throw new Error('Kategori getirilemedi')
    }

    const data = await response.json()
    return NextResponse.json(data.data)
  } catch (error) {
    console.error('Kategori getirilirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
}

export async function PUT(
  request: Request,
  { params }: { params: { id: string } }
) {
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
    const response = await fetch(`http://localhost/cinaralti-website/database/api/categories.php?id=${params.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name,
        slug,
        description,
        type
      }),
    })

    if (!response.ok) {
      if (response.status === 404) {
        return new NextResponse('Not Found', { status: 404 })
      }
      const errorData = await response.json()
      return new NextResponse(errorData.error || 'Kategori güncellenemedi', { status: response.status })
    }

    const data = await response.json()
    
    // Güncellenmiş kategoriyi tekrar getir
    const updatedResponse = await fetch(`http://localhost/cinaralti-website/database/api/categories.php?id=${params.id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })

    if (updatedResponse.ok) {
      const updatedData = await updatedResponse.json()
      return NextResponse.json(updatedData.data)
    }

    return NextResponse.json({ id: params.id, name })
  } catch (error) {
    console.error('Kategori güncellenirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
}

export async function DELETE(
  request: Request,
  { params }: { params: { id: string } }
) {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    // PHP backend'e istek gönder - XAMPP üzerinden doğru adres
    const response = await fetch(`http://localhost/cinaralti-website/database/api/categories.php?id=${params.id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
    })

    if (!response.ok) {
      if (response.status === 404) {
        return new NextResponse('Not Found', { status: 404 })
      }
      const errorData = await response.json()
      return new NextResponse(errorData.error || 'Kategori silinemedi', { status: response.status })
    }

    return new NextResponse(null, { status: 204 })
  } catch (error) {
    console.error('Kategori silinirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 