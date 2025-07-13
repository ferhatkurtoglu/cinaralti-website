'use client'

import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import { CKEditor } from '@ckeditor/ckeditor5-react'
import { useEffect, useState } from 'react'

interface CKEditorProps {
  content: string
  onChange: (content: string) => void
  placeholder?: string
  height?: number
}

export default function CKEditorComponent({ 
  content, 
  onChange, 
  placeholder = 'İçeriğinizi buraya yazın...',
  height = 400 
}: CKEditorProps) {
  const [isReady, setIsReady] = useState(false)

  useEffect(() => {
    setIsReady(true)
  }, [])

  const editorConfiguration = {
    placeholder,
    language: 'tr',
    toolbar: {
      items: [
        'heading',
        '|',
        'bold',
        'italic',
        'underline',
        'strikethrough',
        '|',
        'fontSize',
        'fontFamily',
        'fontColor',
        'fontBackgroundColor',
        '|',
        'bulletedList',
        'numberedList',
        'todoList',
        '|',
        'outdent',
        'indent',
        '|',
        'alignment',
        '|',
        'link',
        'imageUpload',
        'mediaEmbed',
        '|',
        'blockQuote',
        'insertTable',
        'horizontalLine',
        'pageBreak',
        '|',
        'undo',
        'redo',
        '|',
        'sourceEditing',
        'findAndReplace',
        'selectAll',
        '|',
        'specialCharacters',
        'code',
        'codeBlock',
        '|',
        'highlight',
        'removeFormat'
      ],
      shouldNotGroupWhenFull: false
    },
    heading: {
      options: [
        { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
        { model: 'heading1', view: 'h1', title: 'Başlık 1', class: 'ck-heading_heading1' },
        { model: 'heading2', view: 'h2', title: 'Başlık 2', class: 'ck-heading_heading2' },
        { model: 'heading3', view: 'h3', title: 'Başlık 3', class: 'ck-heading_heading3' },
        { model: 'heading4', view: 'h4', title: 'Başlık 4', class: 'ck-heading_heading4' },
        { model: 'heading5', view: 'h5', title: 'Başlık 5', class: 'ck-heading_heading5' },
        { model: 'heading6', view: 'h6', title: 'Başlık 6', class: 'ck-heading_heading6' }
      ]
    },
    fontSize: {
      options: [
        9, 10, 11, 12, 'default', 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36
      ],
      supportAllValues: true
    },
    fontFamily: {
      options: [
        'default',
        'Arial, sans-serif',
        'Helvetica, sans-serif',
        'Times New Roman, serif',
        'Georgia, serif',
        'Verdana, sans-serif',
        'Trebuchet MS, sans-serif',
        'Tahoma, sans-serif',
        'Courier New, monospace',
        'Comic Sans MS, cursive',
        'Impact, sans-serif'
      ],
      supportAllValues: true
    },
    fontColor: {
      colors: [
        { color: '#000000', label: 'Siyah' },
        { color: '#333333', label: 'Koyu Gri' },
        { color: '#666666', label: 'Gri' },
        { color: '#999999', label: 'Açık Gri' },
        { color: '#ffffff', label: 'Beyaz' },
        { color: '#ff0000', label: 'Kırmızı' },
        { color: '#00ff00', label: 'Yeşil' },
        { color: '#0000ff', label: 'Mavi' },
        { color: '#ffff00', label: 'Sarı' },
        { color: '#ff00ff', label: 'Magenta' },
        { color: '#00ffff', label: 'Cyan' },
        { color: '#ffa500', label: 'Turuncu' },
        { color: '#800080', label: 'Mor' },
        { color: '#008000', label: 'Koyu Yeşil' },
        { color: '#000080', label: 'Koyu Mavi' },
        { color: '#800000', label: 'Koyu Kırmızı' }
      ],
      columns: 4,
      documentColors: 10
    },
    fontBackgroundColor: {
      colors: [
        { color: '#ffffff', label: 'Beyaz' },
        { color: '#f0f0f0', label: 'Açık Gri' },
        { color: '#e0e0e0', label: 'Gri' },
        { color: '#d0d0d0', label: 'Koyu Gri' },
        { color: '#ffffe0', label: 'Açık Sarı' },
        { color: '#ffe0e0', label: 'Açık Kırmızı' },
        { color: '#e0ffe0', label: 'Açık Yeşil' },
        { color: '#e0e0ff', label: 'Açık Mavi' },
        { color: '#ffe0ff', label: 'Açık Magenta' },
        { color: '#e0ffff', label: 'Açık Cyan' }
      ],
      columns: 5,
      documentColors: 10
    },
    alignment: {
      options: ['left', 'center', 'right', 'justify']
    },
    link: {
      decorators: {
        openInNewTab: {
          mode: 'manual',
          label: 'Yeni sekmede aç',
          defaultValue: true,
          attributes: {
            target: '_blank',
            rel: 'noopener noreferrer'
          }
        }
      }
    },
    table: {
      contentToolbar: [
        'tableColumn',
        'tableRow',
        'mergeTableCells',
        'tableCellProperties',
        'tableProperties'
      ]
    },
    image: {
      toolbar: [
        'imageTextAlternative',
        'imageStyle:inline',
        'imageStyle:block',
        'imageStyle:side',
        'linkImage'
      ]
    },
    simpleUpload: {
      uploadUrl: '/api/upload',
      withCredentials: true,
      headers: {
        'X-CSRF-TOKEN': 'CSRF-Token'
      }
    },
    mediaEmbed: {
      previewsInData: true,
      providers: [
        {
          name: 'youtube',
          url: /^youtube\.com\/watch\?v=([\w-]+)/,
          html: match => {
            const id = match[1]
            return `<div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
              <iframe src="https://www.youtube.com/embed/${id}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe>
            </div>`
          }
        },
        {
          name: 'vimeo',
          url: /^vimeo\.com\/(\d+)/,
          html: match => {
            const id = match[1]
            return `<div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
              <iframe src="https://player.vimeo.com/video/${id}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" frameborder="0" allowfullscreen></iframe>
            </div>`
          }
        }
      ]
    },
    codeBlock: {
      languages: [
        { language: 'plaintext', label: 'Düz Metin' },
        { language: 'html', label: 'HTML' },
        { language: 'css', label: 'CSS' },
        { language: 'javascript', label: 'JavaScript' },
        { language: 'typescript', label: 'TypeScript' },
        { language: 'php', label: 'PHP' },
        { language: 'python', label: 'Python' },
        { language: 'java', label: 'Java' },
        { language: 'cpp', label: 'C++' },
        { language: 'c', label: 'C' },
        { language: 'csharp', label: 'C#' },
        { language: 'sql', label: 'SQL' },
        { language: 'xml', label: 'XML' },
        { language: 'json', label: 'JSON' },
        { language: 'bash', label: 'Bash' },
        { language: 'powershell', label: 'PowerShell' }
      ]
    },
    highlight: {
      options: [
        { model: 'yellowMarker', class: 'marker-yellow', title: 'Sarı İşaretleyici', color: 'var(--ck-highlight-marker-yellow)', type: 'marker' },
        { model: 'greenMarker', class: 'marker-green', title: 'Yeşil İşaretleyici', color: 'var(--ck-highlight-marker-green)', type: 'marker' },
        { model: 'pinkMarker', class: 'marker-pink', title: 'Pembe İşaretleyici', color: 'var(--ck-highlight-marker-pink)', type: 'marker' },
        { model: 'blueMarker', class: 'marker-blue', title: 'Mavi İşaretleyici', color: 'var(--ck-highlight-marker-blue)', type: 'marker' },
        { model: 'redPen', class: 'pen-red', title: 'Kırmızı Kalem', color: 'var(--ck-highlight-pen-red)', type: 'pen' },
        { model: 'greenPen', class: 'pen-green', title: 'Yeşil Kalem', color: 'var(--ck-highlight-pen-green)', type: 'pen' }
      ]
    },
    wordCount: {
      onUpdate: (stats: any) => {
        // Kelime sayısını gösterebiliriz
        console.log('Kelime sayısı:', stats.words)
        console.log('Karakter sayısı:', stats.characters)
      }
    },
    typing: {
      transformations: {
        remove: [
          'enDash',
          'emDash',
          'oneHalf',
          'oneThird',
          'twoThirds',
          'oneForth',
          'threeQuarters'
        ]
      }
    },
    removePlugins: ['Title'],
    ui: {
      poweredBy: {
        side: 'right',
        label: 'Çınaraltı İçerik Editörü'
      }
    }
  }

  if (!isReady) {
    return (
      <div className="border rounded-lg p-8 bg-gray-50 flex items-center justify-center" style={{ height: `${height}px` }}>
        <div className="text-center">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
          <p className="mt-2 text-sm text-gray-600">Editör yükleniyor...</p>
        </div>
      </div>
    )
  }

  return (
    <div className="border rounded-lg overflow-hidden shadow-sm">
      <CKEditor
        editor={ClassicEditor}
        config={editorConfiguration}
        data={content}
        onReady={(editor) => {
          // Editör yüksekliğini ayarla
          const editorElement = editor.ui.getEditableElement()
          if (editorElement) {
            editorElement.style.minHeight = `${height}px`
          }
          
          // Özel CSS stilleri ekle
          const view = editor.editing.view
          const viewDocument = view.document
          const root = viewDocument.getRoot()
          
          if (root) {
            view.change(writer => {
              writer.setStyle('font-family', 'system-ui, -apple-system, sans-serif', root)
              writer.setStyle('font-size', '16px', root)
              writer.setStyle('line-height', '1.6', root)
            })
          }
          
          console.log('CKEditor yüklendi ve yapılandırıldı')
        }}
        onChange={(event, editor) => {
          const data = editor.getData()
          onChange(data)
        }}
        onBlur={(event, editor) => {
          // Editör odaktan çıktığında otomatik kaydetme tetiklenebilir
          console.log('Editör odaktan çıktı')
        }}
        onFocus={(event, editor) => {
          // Editör odağa geldiğinde analytics kaydetme
          console.log('Editör odağa geldi')
        }}
        onError={(error, { willEditorRestart }) => {
          console.error('CKEditor hatası:', error)
          if (willEditorRestart) {
            console.log('Editör yeniden başlatılacak')
          }
        }}
      />
      
      {/* Editör alt bilgi çubuğu */}
      <div className="bg-gray-50 px-4 py-2 border-t text-xs text-gray-500 flex justify-between items-center">
        <div className="flex items-center space-x-4">
          <span>💡 İpucu: Ctrl+S ile kaydet, Ctrl+Z ile geri al</span>
        </div>
        <div className="flex items-center space-x-2">
          <span>✨ Gelişmiş Editör</span>
        </div>
      </div>
    </div>
  )
} 