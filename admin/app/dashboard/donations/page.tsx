'use client';

import jsPDF from 'jspdf';
import 'jspdf-autotable';
import { useEffect, useRef, useState } from 'react';
import { FaCalendarAlt, FaCheckCircle, FaDownload, FaEye, FaSearch, FaTimesCircle } from 'react-icons/fa';
import * as XLSX from 'xlsx';

interface Donation {
  id: number;
  name: string;
  email: string;
  phone: string;
  type: string;
  amount: string;
  date: string;
  status: 'success' | 'pending' | 'failed';
  payment_method: string;
  notes?: string;
}

export default function Donations() {
  const [donations, setDonations] = useState<Donation[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [search, setSearch] = useState<string>('');
  const [selectedStatus, setSelectedStatus] = useState<string>('all');
  const [selectedType, setSelectedType] = useState<string>('all');
  const [selectedDonation, setSelectedDonation] = useState<Donation | null>(null);
  const [showModal, setShowModal] = useState<boolean>(false);
  const [dateRange, setDateRange] = useState<{from: string, to: string}>({
    from: '',
    to: ''
  });
  const [dropdownOpen, setDropdownOpen] = useState<boolean>(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  // Örnek bağış verileri
  const exampleDonations: Donation[] = [
    {
      id: 1,
      name: 'Ahmet Yılmaz',
      email: 'ahmet@example.com',
      phone: '05551234567',
      type: 'Genel Bağış',
      amount: '500₺',
      date: '2023-06-15T10:30:00',
      status: 'success',
      payment_method: 'Kredi Kartı'
    },
    {
      id: 2,
      name: 'Fatma Demir',
      email: 'fatma@example.com',
      phone: '05559876543',
      type: 'Zekat',
      amount: '1.000₺',
      date: '2023-06-14T15:45:00',
      status: 'success',
      payment_method: 'Havale/EFT'
    },
    {
      id: 3,
      name: 'Mehmet Öztürk',
      email: 'mehmet@example.com',
      phone: '05551112233',
      type: 'Filistin Yardımı',
      amount: '750₺',
      date: '2023-06-13T09:15:00',
      status: 'pending',
      payment_method: 'Kredi Kartı'
    },
    {
      id: 4,
      name: 'Ayşe Kaya',
      email: 'ayse@example.com',
      phone: '05554445566',
      type: 'Yetim Projesi',
      amount: '300₺',
      date: '2023-06-12T17:20:00',
      status: 'success',
      payment_method: 'Kredi Kartı'
    },
    {
      id: 5,
      name: 'Ali Can',
      email: 'ali@example.com',
      phone: '05557778899',
      type: 'Kurban Bağışı',
      amount: '2.500₺',
      date: '2023-06-11T11:10:00',
      status: 'failed',
      payment_method: 'Kredi Kartı',
      notes: 'Ödeme sırasında banka hatası oluştu'
    },
    {
      id: 6,
      name: 'Zeynep Şahin',
      email: 'zeynep@example.com',
      phone: '05553334444',
      type: 'Afrika Bağışı',
      amount: '450₺',
      date: '2023-06-10T14:30:00',
      status: 'success',
      payment_method: 'Havale/EFT'
    },
    {
      id: 7,
      name: 'Mustafa Aydın',
      email: 'mustafa@example.com',
      phone: '05556667788',
      type: 'Kuran Talebelerinin İhtiyaçları',
      amount: '600₺',
      date: '2023-06-09T10:00:00',
      status: 'success',
      payment_method: 'Kredi Kartı'
    },
    {
      id: 8,
      name: 'Elif Yıldız',
      email: 'elif@example.com',
      phone: '05551234567',
      type: 'Bina Satın Alma',
      amount: '5.000₺',
      date: '2023-06-08T16:45:00',
      status: 'success',
      payment_method: 'Havale/EFT'
    },
    {
      id: 9,
      name: 'Emre Çelik',
      email: 'emre@example.com',
      phone: '05559876543',
      type: 'Genel Bağış',
      amount: '200₺',
      date: '2023-06-07T09:30:00',
      status: 'pending',
      payment_method: 'Kredi Kartı'
    },
    {
      id: 10,
      name: 'Sema Koç',
      email: 'sema@example.com',
      phone: '05551112233',
      type: 'Filistin Yardımı',
      amount: '1.500₺',
      date: '2023-06-06T13:20:00',
      status: 'success',
      payment_method: 'Kredi Kartı'
    }
  ];

  useEffect(() => {
    // Mock veri yükleme (gerçek uygulamada API'dan çekilecek)
    setTimeout(() => {
      setDonations(exampleDonations);
      setLoading(false);
    }, 600);
  }, []);

  useEffect(() => {
    // Dropdown dışına tıklama olayını dinle
    function handleClickOutside(event: MouseEvent) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setDropdownOpen(false);
      }
    }
    
    // Event listener'ı ekle
    document.addEventListener("mousedown", handleClickOutside);
    
    // Cleanup
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  const toggleDropdown = () => {
    setDropdownOpen(!dropdownOpen);
  };

  const handleSearch = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearch(e.target.value);
  };

  const handleStatusChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    setSelectedStatus(e.target.value);
  };

  const handleTypeChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    setSelectedType(e.target.value);
  };

  const handleDateRangeChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setDateRange(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleViewDetails = (donation: Donation) => {
    setSelectedDonation(donation);
    setShowModal(true);
  };

  const handleExport = (format: string) => {
    // Filtrelenmemiş verileri dışa aktarma
    const dataToExport = filteredDonations.map(d => ({
      'ID': d.id,
      'Ad Soyad': d.name,
      'E-posta': d.email,
      'Telefon': d.phone,
      'Bağış Türü': d.type,
      'Miktar': d.amount,
      'Tarih': new Date(d.date).toLocaleString('tr-TR'),
      'Durum': d.status === 'success' ? 'Başarılı' : d.status === 'pending' ? 'Beklemede' : 'Başarısız',
      'Ödeme Yöntemi': d.payment_method,
      'Notlar': d.notes || ''
    }));

    if (format === 'excel') {
      try {
        const worksheet = XLSX.utils.json_to_sheet(dataToExport);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Bağışlar');
        
        // Sütun genişliklerini ayarla
        const maxWidth = dataToExport.reduce((w, r) => Math.max(w, r['Ad Soyad'].length), 10);
        worksheet['!cols'] = [
          { wch: 5 }, // ID
          { wch: maxWidth }, // Ad Soyad
          { wch: 25 }, // E-posta
          { wch: 15 }, // Telefon
          { wch: 20 }, // Bağış Türü
          { wch: 10 }, // Miktar
          { wch: 20 }, // Tarih
          { wch: 10 }, // Durum
          { wch: 15 }, // Ödeme Yöntemi
          { wch: 30 }, // Notlar
        ];
        
        // Binary string oluştur
        const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
        
        // Blob oluştur
        const blob = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        
        // Dosyayı indir
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'Bağışlar.xlsx';
        document.body.appendChild(link);
        link.click();
        
        // Temizlik
        setTimeout(() => {
          document.body.removeChild(link);
          URL.revokeObjectURL(url);
        }, 100);
      } catch (error) {
        console.error("Excel dışa aktarma hatası:", error);
        alert("Excel dosyası oluşturulurken bir hata oluştu.");
      }
    } else if (format === 'pdf') {
      try {
        const doc = new jsPDF();
        
        // Başlık
        doc.setFontSize(16);
        doc.text('Bağışlar Raporu', 14, 15);
        doc.setFontSize(10);
        doc.text(`Oluşturulma Tarihi: ${new Date().toLocaleString('tr-TR')}`, 14, 22);
        
        // Tablo
        (doc as any).autoTable({
          head: [['ID', 'Ad Soyad', 'E-posta', 'Telefon', 'Bağış Türü', 'Miktar', 'Tarih', 'Durum', 'Ödeme Yöntemi']],
          body: dataToExport.map(item => [
            item['ID'],
            item['Ad Soyad'],
            item['E-posta'],
            item['Telefon'],
            item['Bağış Türü'],
            item['Miktar'],
            item['Tarih'],
            item['Durum'],
            item['Ödeme Yöntemi']
          ]),
          startY: 25,
          styles: { fontSize: 8, cellPadding: 2 },
          columnStyles: {
            0: { cellWidth: 10 },
            1: { cellWidth: 30 },
            2: { cellWidth: 35 },
            3: { cellWidth: 20 },
            4: { cellWidth: 25 },
            5: { cellWidth: 15 },
            6: { cellWidth: 25 },
            7: { cellWidth: 15 },
            8: { cellWidth: 20 }
          },
          headStyles: { fillColor: [76, 175, 80] }
        });
        
        // Blob olarak kaydet ve indir
        const pdfBlob = doc.output('blob');
        const url = URL.createObjectURL(pdfBlob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'Bağışlar.pdf';
        document.body.appendChild(link);
        link.click();
        
        // Temizlik
        setTimeout(() => {
          document.body.removeChild(link);
          URL.revokeObjectURL(url);
        }, 100);
      } catch (error) {
        console.error("PDF dışa aktarma hatası:", error);
        alert("PDF dosyası oluşturulurken bir hata oluştu.");
      }
    } else if (format === 'csv') {
      // CSV'ye dönüştürme
      const headers = Object.keys(dataToExport[0]).join(',');
      const csvRows = dataToExport.map(row => {
        return Object.values(row).map(value => {
          // Virgülleri ve çift tırnakları kontrol et
          if (typeof value === 'string' && (value.includes(',') || value.includes('"'))) {
            return `"${value.replace(/"/g, '""')}"`;
          }
          return value;
        }).join(',');
      });
      
      const csvContent = [headers, ...csvRows].join('\n');
      
      // CSV indirme
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.setAttribute('href', url);
      link.setAttribute('download', 'Bağışlar.csv');
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      
      // Temizlik
      setTimeout(() => {
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
      }, 100);
    }
  };

  const filteredDonations = donations.filter(donation => {
    // Arama filtresi
    const searchMatch = donation.name.toLowerCase().includes(search.toLowerCase()) || 
                        donation.email.toLowerCase().includes(search.toLowerCase()) ||
                        donation.type.toLowerCase().includes(search.toLowerCase());
    
    // Durum filtresi
    const statusMatch = selectedStatus === 'all' || donation.status === selectedStatus;
    
    // Tip filtresi
    const typeMatch = selectedType === 'all' || donation.type === selectedType;
    
    // Tarih aralığı filtresi
    let dateMatch = true;
    if (dateRange.from && dateRange.to) {
      const donationDate = new Date(donation.date);
      const fromDate = new Date(dateRange.from);
      const toDate = new Date(dateRange.to);
      toDate.setHours(23, 59, 59); // Bitiş tarihini günün sonuna ayarla
      
      dateMatch = donationDate >= fromDate && donationDate <= toDate;
    }
    
    return searchMatch && statusMatch && typeMatch && dateMatch;
  });

  // Benzersiz bağış tiplerini bul
  const donationTypes = [...new Set(donations.map(donation => donation.type))];

  return (
    <div>
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 className="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Bağışlar</h1>
        
        <div className="dropdown relative" ref={dropdownRef}>
          <button 
            className="btn btn-primary flex items-center dropdown-toggle"
            onClick={toggleDropdown}
          >
            <FaDownload className="mr-2" />
            Dışa Aktar
          </button>
          {dropdownOpen && (
            <div className="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-50">
              <button
                onClick={() => {
                  handleExport('excel');
                  setDropdownOpen(false);
                }}
                className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                Excel
              </button>
              <button
                onClick={() => {
                  handleExport('pdf');
                  setDropdownOpen(false);
                }}
                className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                PDF
              </button>
              <button
                onClick={() => {
                  handleExport('csv');
                  setDropdownOpen(false);
                }}
                className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                CSV
              </button>
            </div>
          )}
        </div>
      </div>

      <div className="card mb-6">
        <div className="p-4 border-b border-gray-200">
          <div className="flex flex-col md:flex-row gap-4">
            <div className="flex-1 relative">
              <input
                type="text"
                className="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="İsim, e-posta veya bağış tipine göre ara..."
                value={search}
                onChange={handleSearch}
              />
              <FaSearch className="absolute left-3 top-3 text-gray-400" />
            </div>
            
            <div className="flex flex-wrap gap-4">
              <div className="w-40">
                <select
                  value={selectedStatus}
                  onChange={handleStatusChange}
                  className="select-field w-full"
                  aria-label="Durum filtresi"
                >
                  <option value="all">Tüm Durumlar</option>
                  <option value="success">Tamamlandı</option>
                  <option value="pending">Bekliyor</option>
                  <option value="failed">Başarısız</option>
                </select>
              </div>
              
              <div className="w-40">
                <select
                  value={selectedType}
                  onChange={handleTypeChange}
                  className="select-field w-full"
                  aria-label="Bağış tipi filtresi"
                >
                  <option value="all">Tüm Tipler</option>
                  {donationTypes.map((type, index) => (
                    <option key={index} value={type}>{type}</option>
                  ))}
                </select>
              </div>
              
              <div className="flex gap-2 items-center">
                <FaCalendarAlt className="text-gray-400" />
                <input
                  type="date"
                  name="from"
                  value={dateRange.from}
                  onChange={handleDateRangeChange}
                  className="input-field py-1 w-32"
                  aria-label="Başlangıç tarihi"
                />
                <span>-</span>
                <input
                  type="date"
                  name="to"
                  value={dateRange.to}
                  onChange={handleDateRangeChange}
                  className="input-field py-1 w-32"
                  aria-label="Bitiş tarihi"
                />
              </div>
            </div>
          </div>
        </div>
        
        {loading ? (
          <div className="p-8 text-center">
            <div className="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-primary border-r-transparent"></div>
            <p className="mt-2 text-gray-600">Yükleniyor...</p>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bağışçı</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bağış Tipi</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Miktar</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                  <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {filteredDonations.length > 0 ? (
                  filteredDonations.map(donation => (
                    <tr key={donation.id}>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">#{donation.id}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm font-medium text-gray-900">{donation.name}</div>
                        <div className="text-sm text-gray-500">{donation.email}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">{donation.type}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm font-semibold text-gray-900">{donation.amount}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">{new Date(donation.date).toLocaleDateString('tr-TR')}</div>
                        <div className="text-xs text-gray-500">{new Date(donation.date).toLocaleTimeString('tr-TR')}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                          donation.status === 'success' ? 'bg-green-100 text-green-800' : 
                          donation.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                          'bg-red-100 text-red-800'
                        }`}>
                          {donation.status === 'success' ? (
                            <>
                              <FaCheckCircle className="mr-1" />
                              Tamamlandı
                            </>
                          ) : donation.status === 'pending' ? (
                            <>
                              <FaTimesCircle className="mr-1" />
                              Bekliyor
                            </>
                          ) : (
                            <>
                              <FaTimesCircle className="mr-1" />
                              Başarısız
                            </>
                          )}
                        </span>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button
                          onClick={() => handleViewDetails(donation)}
                          className="text-primary hover:text-primary-dark mr-3"
                          title="Bağış detaylarını görüntüle"
                          aria-label="Bağış detaylarını görüntüle"
                        >
                          <FaEye />
                        </button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={7} className="px-6 py-4 text-center text-sm text-gray-500">
                      Belirtilen kriterlere uygun bağış bulunamadı
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Bağış Detayları Modal */}
      {showModal && selectedDonation && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div className="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
              <h3 className="text-lg font-semibold text-gray-900">
                Bağış Detayları
              </h3>
              <button
                onClick={() => setShowModal(false)}
                className="text-gray-400 hover:text-gray-500"
              >
                &times;
              </button>
            </div>
            
            <div className="p-6">
              <div className="mb-4">
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">Bağış ID:</span>
                  <span className="font-medium">#{selectedDonation.id}</span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">Bağışçı Adı:</span>
                  <span className="font-medium">{selectedDonation.name}</span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">E-posta:</span>
                  <span className="font-medium">{selectedDonation.email}</span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">Telefon:</span>
                  <span className="font-medium">{selectedDonation.phone}</span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">Bağış Tipi:</span>
                  <span className="font-medium">{selectedDonation.type}</span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">Miktar:</span>
                  <span className="font-medium">{selectedDonation.amount}</span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">Tarih:</span>
                  <span className="font-medium">
                    {new Date(selectedDonation.date).toLocaleDateString('tr-TR')} {new Date(selectedDonation.date).toLocaleTimeString('tr-TR')}
                  </span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">Ödeme Yöntemi:</span>
                  <span className="font-medium">{selectedDonation.payment_method}</span>
                </div>
                <div className="flex justify-between mb-2">
                  <span className="text-gray-500">Durum:</span>
                  <span className={`font-medium ${
                    selectedDonation.status === 'success' ? 'text-green-600' : 
                    selectedDonation.status === 'pending' ? 'text-yellow-600' : 
                    'text-red-600'
                  }`}>
                    {selectedDonation.status === 'success' ? 'Tamamlandı' : 
                     selectedDonation.status === 'pending' ? 'Bekliyor' : 
                     'Başarısız'}
                  </span>
                </div>
                {selectedDonation.notes && (
                  <div className="mt-4">
                    <span className="text-gray-500 block mb-1">Notlar:</span>
                    <p className="bg-gray-50 p-3 rounded text-sm">{selectedDonation.notes}</p>
                  </div>
                )}
              </div>
              
              <div className="mt-6 flex justify-end space-x-3">
                <button
                  onClick={() => setShowModal(false)}
                  className="btn btn-secondary"
                >
                  Kapat
                </button>
                {selectedDonation.status === 'pending' && (
                  <button
                    className="btn btn-primary"
                    onClick={() => {
                      alert('Bağış durumu güncellendi');
                      setShowModal(false);
                    }}
                  >
                    Tamamlandı Olarak İşaretle
                  </button>
                )}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
} 