import { getServerSession } from 'next-auth'
import { NextResponse } from 'next/server'
import { authOptions } from '../auth/[...nextauth]/route'

export async function GET() {
  try {
    const session = await getServerSession(authOptions)

    if (!session) {
      return new NextResponse('Unauthorized', { status: 401 })
    }

    // PHP backend'e istek gönder
    const response = await fetch('http://localhost/cinaralti-website/database/api/blog.php', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })

    if (!response.ok) {
      throw new Error('Blog yazıları getirilemedi')
    }

    const data = await response.json()
    
    // Yanıtı Next.js formatına dönüştür
    const posts = data.data.map((post: any) => ({
      id: post.id,
      title: post.title,
      content: post.content,
      excerpt: post.excerpt,
      slug: post.slug,
      status: post.status,
      featured: post.featured,
      coverImage: post.cover_image,
      tags: post.tags,
      createdAt: post.created_at,
      updatedAt: post.updated_at,
      author: {
        name: post.author_name,
      },
      category: post.category_name ? {
        name: post.category_name,
      } : null,
    }))

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

    if (!title || !content || !status) {
      return new NextResponse('Missing required fields', { status: 400 })
    }

    // PHP backend'e post oluşturma isteği gönder
    const response = await fetch('http://localhost/cinaralti-website/database/api/blog.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        title,
        slug,
        content,
        excerpt: excerpt || null,
        status,
        featured: featured || false,
        category_id: categoryId || null,
        tags: tags || null,
        cover_image: coverImage || null,
        author_id: 1, // Şimdilik default author
      }),
    })

    if (!response.ok) {
      const error = await response.json()
      return NextResponse.json({ error: error.error || 'Blog yazısı oluşturulamadı' }, { status: 400 })
    }

    const data = await response.json()
    
    // Oluşturulan post'u getir
    const postResponse = await fetch(`http://localhost/cinaralti-website/database/api/blog.php?id=${data.data.id}`)
    const postData = await postResponse.json()
    
    // Yanıtı Next.js formatına dönüştür
    const post = {
      id: postData.data.id,
      title: postData.data.title,
      content: postData.data.content,
      excerpt: postData.data.excerpt,
      slug: postData.data.slug,
      status: postData.data.status,
      featured: postData.data.featured,
      coverImage: postData.data.cover_image,
      tags: postData.data.tags,
      createdAt: postData.data.created_at,
      updatedAt: postData.data.updated_at,
      author: {
        name: postData.data.author_name,
      },
      category: postData.data.category_name ? {
        name: postData.data.category_name,
      } : null,
    }

    return NextResponse.json(post)
  } catch (error) {
    console.error('Blog yazısı oluşturulurken hata oluştu:', error)
    return new NextResponse('Internal Server Error', { status: 500 })
  }
} 