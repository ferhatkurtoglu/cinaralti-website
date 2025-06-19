import prisma from '@/lib/prisma'
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

    const category = await prisma.contentCategory.findUnique({
      where: {
        id: params.id,
      },
    })

    if (!category) {
      return new NextResponse('Not Found', { status: 404 })
    }

    return NextResponse.json(category)
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
    const { name } = body

    if (!name) {
      return new NextResponse('Missing required fields', { status: 400 })
    }

    const category = await prisma.contentCategory.update({
      where: {
        id: params.id,
      },
      data: {
        name,
      },
    })

    return NextResponse.json(category)
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

    // Kategoriye ait blog yazılarını kontrol et
    const posts = await prisma.contentBlogPost.findMany({
      where: {
        categoryId: params.id,
      },
    })

    if (posts.length > 0) {
      return new NextResponse(
        'Bu kategoriye ait blog yazıları var. Önce blog yazılarını silmelisiniz.',
        { status: 400 }
      )
    }

    await prisma.contentCategory.delete({
      where: {
        id: params.id,
      },
    })

    return new NextResponse(null, { status: 204 })
  } catch (error) {
    console.error('Kategori silinirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 