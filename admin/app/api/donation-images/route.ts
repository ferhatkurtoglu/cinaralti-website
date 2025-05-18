import fs from 'fs';
import { NextRequest, NextResponse } from 'next/server';
import path from 'path';
import { v4 as uuidv4 } from 'uuid';

// İzin verilen dosya uzantıları
const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp', '.gif'];
// Maksimum dosya boyutu (5MB)
const MAX_FILE_SIZE = 5 * 1024 * 1024;

/**
 * Bağış görselleri listeleme API endpoint'i
 */
export async function GET(request: NextRequest) {
  try {
    // Public dosya yolları
    const donateImagesPath = path.join(process.cwd(), 'public/assets/image/donate');
    const generalImagesPath = path.join(process.cwd(), 'public/assets/image');
    
    // Klasörlerin varlığını kontrol et ve oluştur
    if (!fs.existsSync(donateImagesPath)) {
      fs.mkdirSync(donateImagesPath, { recursive: true });
    }
    
    // Tüm görselleri depolamak için dizi
    const allImages: Array<{path: string, name: string, url: string, folder: string, size: number, date: Date}> = [];
    
    // Bağış görselleri klasörünü oku
    if (fs.existsSync(donateImagesPath)) {
      const donateFiles = fs.readdirSync(donateImagesPath);
      
      for (const file of donateFiles) {
        const filePath = path.join(donateImagesPath, file);
        const extension = path.extname(file).toLowerCase();
        
        // Sadece izin verilen uzantılara sahip dosyaları filtrele
        if (allowedExtensions.includes(extension) && fs.statSync(filePath).isFile()) {
          const stats = fs.statSync(filePath);
          allImages.push({
            path: filePath,
            name: file,
            url: `/assets/image/donate/${file}`,
            folder: 'donate',
            size: stats.size,
            date: stats.mtime
          });
        }
      }
    }
    
    // Genel görseller klasörünü oku
    if (fs.existsSync(generalImagesPath)) {
      const generalFiles = fs.readdirSync(generalImagesPath)
        .filter(file => !fs.statSync(path.join(generalImagesPath, file)).isDirectory()); // Sadece dosyaları al, klasörleri alma
      
      for (const file of generalFiles) {
        const filePath = path.join(generalImagesPath, file);
        const extension = path.extname(file).toLowerCase();
        
        // Sadece izin verilen uzantılara sahip dosyaları filtrele
        if (allowedExtensions.includes(extension) && fs.statSync(filePath).isFile()) {
          const stats = fs.statSync(filePath);
          allImages.push({
            path: filePath,
            name: file,
            url: `/assets/image/${file}`,
            folder: 'general',
            size: stats.size,
            date: stats.mtime
          });
        }
      }
    }
    
    // Tarih sırasına göre sırala (en yeni en üstte)
    allImages.sort((a, b) => b.date.getTime() - a.date.getTime());
    
    return NextResponse.json({
      success: true,
      images: allImages
    });
    
  } catch (error) {
    console.error('Görsel listeleme hatası:', error);
    return NextResponse.json(
      { error: 'Görseller listelenirken bir hata oluştu' },
      { status: 500 }
    );
  }
}

/**
 * Bağış görseli yükleme API endpoint'i
 */
export async function POST(request: NextRequest) {
  try {
    // Dosya içeriğini al
    const formData = await request.formData();
    const file = formData.get('file') as File;
    
    if (!file) {
      return NextResponse.json(
        { error: 'Dosya bulunamadı' },
        { status: 400 }
      );
    }
    
    // Dosya boyutunu kontrol et
    if (file.size > MAX_FILE_SIZE) {
      return NextResponse.json(
        { error: 'Dosya boyutu çok büyük. En fazla 5MB olabilir.' },
        { status: 400 }
      );
    }
    
    // Dosya uzantısını kontrol et
    const fileName = file.name;
    const extension = path.extname(fileName).toLowerCase();
    
    if (!allowedExtensions.includes(extension)) {
      return NextResponse.json(
        { error: 'Geçersiz dosya türü. Sadece jpg, jpeg, png, webp ve gif dosyaları yüklenebilir.' },
        { status: 400 }
      );
    }
    
    // Rastgele bir dosya adı oluştur
    const uniqueFileName = `${uuidv4()}${extension}`;
    
    // Public dosya yolu
    const publicPath = 'public/assets/image/donate';
    const filePath = path.join(process.cwd(), publicPath, uniqueFileName);
    
    // Dosya klasörünün var olduğundan emin ol
    const dirPath = path.join(process.cwd(), publicPath);
    if (!fs.existsSync(dirPath)) {
      fs.mkdirSync(dirPath, { recursive: true });
    }
    
    // Dosyayı oluştur
    const fileBuffer = Buffer.from(await file.arrayBuffer());
    fs.writeFileSync(filePath, fileBuffer);
    
    // Göreceli URL yolunu döndür
    const relativeUrl = `/assets/image/donate/${uniqueFileName}`;
    
    return NextResponse.json({
      success: true,
      fileName: uniqueFileName,
      url: relativeUrl
    });
    
  } catch (error) {
    console.error('Dosya yükleme hatası:', error);
    return NextResponse.json(
      { error: 'Dosya yüklenirken bir hata oluştu' },
      { status: 500 }
    );
  }
}

/**
 * Bağış görseli silme API endpoint'i
 */
export async function DELETE(request: NextRequest) {
  try {
    const { fileName } = await request.json();
    
    if (!fileName) {
      return NextResponse.json(
        { error: 'Dosya adı belirtilmedi' },
        { status: 400 }
      );
    }
    
    // Güvenlik kontrolü - sadece izin verilen dosya uzantılarını kabul et
    const extension = path.extname(fileName).toLowerCase();
    if (!allowedExtensions.includes(extension)) {
      return NextResponse.json(
        { error: 'Geçersiz dosya türü' },
        { status: 400 }
      );
    }
    
    // Güvenlik kontrolü - dosya adının sadece alfanumerik, - ve . karakterlerini içermesini sağla
    if (!/^[a-zA-Z0-9\-\.]+$/.test(fileName)) {
      return NextResponse.json(
        { error: 'Geçersiz dosya adı' },
        { status: 400 }
      );
    }
    
    // Dosya yolu
    const filePath = path.join(process.cwd(), 'public/assets/image/donate', fileName);
    
    // Dosyanın var olduğunu kontrol et
    if (!fs.existsSync(filePath)) {
      return NextResponse.json(
        { error: 'Dosya bulunamadı' },
        { status: 404 }
      );
    }
    
    // Dosyayı sil
    fs.unlinkSync(filePath);
    
    return NextResponse.json({
      success: true,
      message: 'Dosya başarıyla silindi'
    });
    
  } catch (error) {
    console.error('Dosya silme hatası:', error);
    return NextResponse.json(
      { error: 'Dosya silinirken bir hata oluştu' },
      { status: 500 }
    );
  }
} 