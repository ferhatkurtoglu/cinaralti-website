import prisma from '@/lib/prisma'
import { getServerSession } from 'next-auth'
import { NextResponse } from 'next/server'
import { authOptions } from '../../auth/[...nextauth]/route'

interface Params {
  params: {
    id: string
  }
}

export async function GET(request: Request, { params }: Params) {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    const video = await prisma.contentVideo.findUnique({
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

    if (!video) {
      return new NextResponse('Video not found', { status: 404 })
    }

    return NextResponse.json(video)
  } catch (error) {
    console.error('Video getirilirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
}

export async function PUT(request: Request, { params }: Params) {
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

    const video = await prisma.contentVideo.update({
      where: {
        id: params.id,
      },
      data: {
        title,
        description,
        url,
        thumbnail,
        status,
        featured: featured || false,
        categoryId: categoryId || null,
        tags,
      },
    })

    return NextResponse.json(video)
  } catch (error) {
    console.error('Video güncellenirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    await prisma.contentVideo.delete({
      where: {
        id: params.id,
      },
    })

    return new NextResponse(null, { status: 204 })
  } catch (error) {
    console.error('Video silinirken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 