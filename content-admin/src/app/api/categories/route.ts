import prisma from '@/lib/prisma'
import { getServerSession } from 'next-auth'
import { NextResponse } from 'next/server'
import { authOptions } from '../auth/[...nextauth]/route'

export async function GET() {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    const categories = await prisma.blogCategory.findMany({
      orderBy: {
        name: 'asc',
      },
    })

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

    // Slug oluştur (eğer verilmemişse)
    const finalSlug = slug || name
      .toLowerCase()
      .replace(/ğ/g, 'g')
      .replace(/ü/g, 'u')
      .replace(/ş/g, 's')
      .replace(/ı/g, 'i')
      .replace(/ö/g, 'o')
      .replace(/ç/g, 'c')
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .trim()

    const category = await prisma.blogCategory.create({
      data: {
        name,
        slug: finalSlug,
        description: description || null,
        type: type || 'blog',
      },
    })

    return NextResponse.json(category)
  } catch (error) {
    console.error('Kategori oluşturulurken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 