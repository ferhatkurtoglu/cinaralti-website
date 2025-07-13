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

    const posts = await prisma.blogPost.findMany({
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
      orderBy: {
        createdAt: 'desc',
      },
    })

    return NextResponse.json(posts)
  } catch (error) {
    console.error('Blog yazıları getirilirken hata oluştu:', error)
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
    const { title, slug, content, excerpt, status, featured, categoryId, tags, coverImage } = body

    if (!title || !slug || !content || !status) {
      return new NextResponse('Missing required fields', { status: 400 })
    }

    // Slug'ın benzersiz olup olmadığını kontrol et
    const existingPost = await prisma.blogPost.findUnique({
      where: { slug }
    })

    if (existingPost) {
      return NextResponse.json({ error: 'Bu slug zaten kullanılıyor' }, { status: 400 })
    }

    const post = await prisma.blogPost.create({
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
        authorId: session.user.id,
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
    console.error('Blog yazısı oluşturulurken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 