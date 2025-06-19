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

    const categories = await prisma.contentCategory.findMany({
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
    const { name } = body

    if (!name) {
      return new NextResponse('Missing required fields', { status: 400 })
    }

    const category = await prisma.contentCategory.create({
      data: {
        name,
      },
    })

    return NextResponse.json(category)
  } catch (error) {
    console.error('Kategori oluşturulurken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 