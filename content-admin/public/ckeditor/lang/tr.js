/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.plugins.setLang( 'a11yhelp', 'tr', {
	title: 'Erişilebilirlik Talimatları',
	contents: 'Yardım içeriği. Bu pencereyi kapatmak için ESC tuşuna basın.',
	legend: [
		{
		name: 'Genel',
		items: [
			{
			name: 'Editör Araç Çubuğu',
			legend: 'Araç çubuğunda gezinmek için ${toolbarFocus} kullanın. Önceki ve sonraki araç çubuğu grubu için TAB ve SHIFT+TAB kullanın. Önceki ve sonraki araç çubuğu düğmesi için SAĞ OK ve SOL OK kullanın. SPACE tuşu veya ENTER araç çubuğu düğmesini etkinleştirir.'
		},

			{
			name: 'Editör Dialog',
			legend: 'Dialog içinde gezinmek için TAB tuşuna basın, sonraki dialog elemanına geçmek için SHIFT+TAB kullanın, dialogu göndermek için ENTER tuşuna basın, dialogu iptal etmek için ESC tuşuna basın. Birden fazla sekme sayfası olan dialoglar için, sekme listesine gitmek için ALT+F10 kullanın. Sonraki sekme sayfasına geçmek için TAB veya SAĞ OK kullanın. Önceki sekme sayfasına geçmek için SHIFT+TAB veya SOL OK kullanın. SPACE tuşu veya ENTER sekme sayfasını seçer.'
		},

			{
			name: 'Editör İçerik Menüsü',
			legend: 'İçerik menüsünü açmak için ${contextMenu} veya APPLICATION KEY kullanın. Sonraki menü seçeneği için TAB veya AŞAĞI OK kullanın. Önceki seçenek için SHIFT+TAB veya YUKARI OK kullanın. SPACE tuşu veya ENTER menü seçeneğini seçer. Alt menüyü açmak için SPACE tuşu veya ENTER veya SAĞ OK kullanın. Ana menüye dönmek için ESC veya SOL OK kullanın. İçerik menüsünü kapatmak için ESC kullanın.'
		},

			{
			name: 'Editör Liste Kutusu',
			legend: 'Liste kutusu içinde gezinmek için TAB veya AŞAĞI OK kullanın. Sonraki seçenek için SHIFT+TAB veya YUKARI OK kullanın. SPACE tuşu veya ENTER seçeneği seçer. Liste kutusunu kapatmak için ESC kullanın.'
		},

			{
			name: 'Editör Öğe Yolu Çubuğu',
			legend: 'Öğe yolu çubuğunda gezinmek için ${elementsPathFocus} kullanın. Sonraki öğe için TAB veya SAĞ OK kullanın. Önceki öğe için SHIFT+TAB veya SOL OK kullanın. SPACE tuşu veya ENTER öğeyi editörde seçer.'
		}
		]
	},
		{
		name: 'Komutlar',
		items: [
			{
			name: 'Geri Al Komutu',
			legend: '${undo} tuşuna basın'
		},
			{
			name: 'Yinele Komutu',
			legend: '${redo} tuşuna basın'
		},
			{
			name: 'Kalın Komutu',
			legend: '${bold} tuşuna basın'
		},
			{
			name: 'İtalik Komutu',
			legend: '${italic} tuşuna basın'
		},
			{
			name: 'Altı Çizili Komutu',
			legend: '${underline} tuşuna basın'
		},
			{
			name: 'Bağlantı Komutu',
			legend: '${link} tuşuna basın'
		},
			{
			name: 'Araç Çubuğunu Gizle Komutu',
			legend: '${toolbarCollapse} tuşuna basın'
		},
			{
			name: 'Önceki Odak Alanına Erişim Komutu',
			legend: 'Düzenleme alanına geçmeden önce en yakın odaklanabilir alana ulaşmak için ${accessPreviousSpace} tuşuna basın, örneğin: iki bitişik HR elementleri. Uzak alanlara ulaşmak için bu tuş kombinasyonunu tekrarlayın.'
		},
			{
			name: 'Sonraki Odak Alanına Erişim Komutu',
			legend: 'Düzenleme alanından sonra en yakın odaklanabilir alana ulaşmak için ${accessNextSpace} tuşuna basın, örneğin: iki bitişik HR elementleri. Uzak alanlara ulaşmak için bu tuş kombinasyonunu tekrarlayın.'
		},
			{
			name: 'Erişilebilirlik Yardımı',
			legend: '${a11yHelp} tuşuna basın'
		},
			{
			name: ' Paste as plain text',
			legend: 'Press ${pastetext}',
			legendEdge: 'Press ${pastetext}, followed by ${paste}'
		}
		]
	}
	],
	tab: 'Tab',
	pause: 'Pause',
	capslock: 'Caps Lock',
	escape: 'Escape',
	pageUp: 'Page Up',
	pageDown: 'Page Down',
	leftArrow: 'Sol Ok',
	upArrow: 'Yukarı Ok',
	rightArrow: 'Sağ Ok',
	downArrow: 'Aşağı Ok',
	insert: 'Insert',
	leftWindowKey: 'Sol Windows tuşu',
	rightWindowKey: 'Sağ Windows tuşu',
	selectKey: 'Select tuşu',
	numpad0: 'Numpad 0',
	numpad1: 'Numpad 1',
	numpad2: 'Numpad 2',
	numpad3: 'Numpad 3',
	numpad4: 'Numpad 4',
	numpad5: 'Numpad 5',
	numpad6: 'Numpad 6',
	numpad7: 'Numpad 7',
	numpad8: 'Numpad 8',
	numpad9: 'Numpad 9',
	multiply: 'Çarp',
	add: 'Topla',
	subtract: 'Çıkar',
	decimalPoint: 'Ondalık Nokta',
	divide: 'Böl',
	f1: 'F1',
	f2: 'F2',
	f3: 'F3',
	f4: 'F4',
	f5: 'F5',
	f6: 'F6',
	f7: 'F7',
	f8: 'F8',
	f9: 'F9',
	f10: 'F10',
	f11: 'F11',
	f12: 'F12',
	numLock: 'Num Lock',
	scrollLock: 'Scroll Lock',
	semiColon: 'Noktalı Virgül',
	equalSign: 'Eşittir İşareti',
	comma: 'Virgül',
	dash: 'Tire',
	period: 'Nokta',
	forwardSlash: 'İleri Slash',
	graveAccent: 'Grave Aksan',
	openBracket: 'Açık Parantez',
	backSlash: 'Geri Slash',
	closeBracket: 'Kapalı Parantez',
	singleQuote: 'Tek Tırnak'
} ); 