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

    const videos = await prisma.video.findMany({
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

    const video = await prisma.video.create({
      data: {
        title,
        description,
        url,
        thumbnail,
        status,
        featured: featured || false,
        categoryId: categoryId || null,
        tags,
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

    return NextResponse.json(video)
  } catch (error) {
    console.error('Video oluşturulurken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 