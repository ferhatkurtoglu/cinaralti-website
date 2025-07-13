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

    const post = await prisma.blogPost.findUnique({
      where: {
        id: params.id,
      },
      include: {
        author: {
          select: {
            name: true,
          },
        },
        category: {
          select: {
            name: true,
          },
        },
      },
    })

    if (!post) {
      return new NextResponse('Not Found', { status: 404 })
    }

    return NextResponse.json(post)
  } catch (error) {
    console.error('Blog yazısı getirilirken hata oluştu:', error)
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
    const { title, slug, content, excerpt, status, featured, categoryId, tags, coverImage } = body

    if (!title || !slug || !content || !status) {
      return new NextResponse('Missing required fields', { status: 400 })
    }

    // Mevcut yazıyı getir
    const existingPost = await prisma.blogPost.findUnique({
      where: { id: params.id },
    })

    if (!existingPost) {
      return new NextResponse('Not Found', { status: 404 })
    }

    // Eğer slug değişmişse, yeni slug'ın benzersiz olup olmadığını kontrol et
    if (slug !== existingPost.slug) {
      const slugExists = await prisma.blogPost.findUnique({
        where: { slug }
      })

      if (slugExists) {
        return NextResponse.json({ error: 'Bu slug zaten kullanılıyor' }, { status: 400 })
      }
    }

    const post = await prisma.blogPost.update({
      where: {
        id: params.id,
      },
      data: {
        title,
        slug,
        content,
        excerpt: excerpt || null,
        status,
        featured: featured || false,
        categoryId: categoryId || null,
        tags: tags || null,
        coverImage: coverImage || null,
      },
      include: {
        author: {
          select: {
            name: true,
          },
        },
        category: {
          select: {
            name: true,
          },
        },
      },
    })

    return NextResponse.json(post)
  } catch (error) {
    console.error('Blog yazısı güncellenirken hata oluştu:', error)
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

    const post = await prisma.blogPost.findUnique({
      where: { id: params.id },
    })

    if (!post) {
      return new NextResponse('Not Found', { status: 404 })
    }

    await prisma.blogPost.delete({
      where: { id: params.id },
    })

    return new NextResponse('OK', { status: 200 })
  } catch (error) {
    console.error('Blog yazısı silinirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 