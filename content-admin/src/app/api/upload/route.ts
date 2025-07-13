import { existsSync } from 'fs'
import { mkdir, writeFile } from 'fs/promises'
import { NextRequest, NextResponse } from 'next/server'
import path from 'path'

export async function POST(request: NextRequest) {
  try {
    const data = await request.formData()
    const file: File | null = data.get('file') as unknown as File

    if (!file) {
      return NextResponse.json({ error: 'Dosya bulunamadı' }, { status: 400 })
    }

    // Dosya türü kontrolü
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
    if (!allowedTypes.includes(file.type)) {
      return NextResponse.json({ error: 'Geçersiz dosya türü. Sadece resim dosyaları yüklenebilir.' }, { status: 400 })
    }

    // Dosya boyutu kontrolü (5MB max)
    if (file.size > 5 * 1024 * 1024) {
      return NextResponse.json({ error: 'Dosya boyutu 5MB\'dan büyük olamaz.' }, { status: 400 })
    }

    const bytes = await file.arrayBuffer()
    const buffer = Buffer.from(bytes)

    // Benzersiz dosya adı oluştur
    const timestamp = Date.now()
    const originalName = file.name.replace(/[^a-zA-Z0-9.-]/g, '_')
    const fileName = `${timestamp}_${originalName}`

    // Uploads klasörünü oluştur (yoksa)
    const uploadsDir = path.join(process.cwd(), 'public', 'uploads', 'blog')
    
    if (!existsSync(uploadsDir)) {
      await mkdir(uploadsDir, { recursive: true })
      console.log('Uploads klasörü oluşturuldu:', uploadsDir)
    }

    // Dosyayı kaydet
    const filePath = path.join(uploadsDir, fileName)
    await writeFile(filePath, buffer)

    // Dosya URL'sini döndür
    const fileUrl = `/uploads/blog/${fileName}`

    console.log('Dosya başarıyla yüklendi:', fileUrl)
    return NextResponse.json({ url: fileUrl }, { status: 200 })
  } catch (error) {
    console.error('Dosya yükleme hatası:', error)
    return NextResponse.json({ 
      error: 'Dosya yüklenirken bir hata oluştu', 
      details: error instanceof Error ? error.message : 'Bilinmeyen hata' 
    }, { status: 500 })
  }
} 