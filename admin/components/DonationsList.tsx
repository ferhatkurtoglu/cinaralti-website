import { saveAs } from 'file-saver';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import Link from 'next/link';
import { useEffect, useState } from 'react';
import { FaDownload, FaEye, FaFileExcel, FaFilePdf, FaSearch } from 'react-icons/fa';
import * as XLSX from 'xlsx';

interface Donation {
  id: number;
  donor_name: string;
  donor_email: string;
  donor_phone: string;
  donor_type: string;
  donation_categori: string;
  amount: number | string;
  created_at: string;
  payment_status: string;
  payment_method?: string;
  city?: string;
  notes?: string;
}

interface PaginationProps {
  currentPage: number;
  totalPages: number;
  totalRecords: number;
  limit: number;
}

interface Filters {
  donationTypes: Array<{donation_type: string}>;
}

export default function DonationsList() {
  const [donations, setDonations] = useState<Donation[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [search, setSearch] = useState<string>('');
  const [selectedStatus, setSelectedStatus] = useState<string>('');
  const [selectedType, setSelectedType] = useState<string>('');
  const [dateRange, setDateRange] = useState<{startDate: string, endDate: string}>({
    startDate: '',
    endDate: ''
  });
  const [pagination, setPagination] = useState<PaginationProps>({
    currentPage: 1,
    totalPages: 1,
    totalRecords: 0,
    limit: 20
  });
  const [filters, setFilters] = useState<Filters>({
    donationTypes: []
  });
  const [dropdownOpen, setDropdownOpen] = useState<boolean>(false);

  // Bağışları getir
  const fetchDonations = async () => {
    setLoading(true);
    try {
      // URL parametreleri oluştur
      const params = new URLSearchParams();
      params.append('page', pagination.currentPage.toString());
      params.append('limit', pagination.limit.toString());
      
      if (selectedStatus) params.append('status', selectedStatus);
      if (selectedType) params.append('type', selectedType);
      if (dateRange.startDate) params.append('startDate', dateRange.startDate);
      if (dateRange.endDate) params.append('endDate', dateRange.endDate);
      if (search) params.append('search', search);
      
      // API isteği yap
      const response = await fetch(`/api/donations?${params.toString()}`);
      if (!response.ok) throw new Error('Veriler getirilirken bir hata oluştu');
      
      const data = await response.json();
      setDonations(data.donations);
      setPagination(data.pagination);
      setFilters(data.filters);
    } catch (error) {
      console.error('Bağışları getirirken hata:', error);
    } finally {
      setLoading(false);
    }
  };

  // İlk yükleme ve filtre değişikliklerinde verileri getir
  useEffect(() => {
    fetchDonations();
  }, [pagination.currentPage, selectedStatus, selectedType, dateRange]);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    // Arama yapıldığında her zaman ilk sayfadan başla
    setPagination({...pagination, currentPage: 1});
    fetchDonations();
  };

  const handlePageChange = (newPage: number) => {
    if (newPage >= 1 && newPage <= pagination.totalPages) {
      setPagination({...pagination, currentPage: newPage});
    }
  };

  const toggleDropdown = () => {
    setDropdownOpen(!dropdownOpen);
  };

  const handleExportPDF = () => {
    const doc = new jsPDF();
    
    // PDF başlığı
    doc.text('Bağışlar Listesi', 14, 16);
    
    // Tablo datası
    const tableData = donations.map(donation => [
      donation.id,
      donation.donor_name,
      donation.donor_email,
      donation.donation_categori,
      `${parseFloat(donation.amount as string).toFixed(2)}₺`,
      getStatusText(donation.payment_status),
      new Date(donation.created_at).toLocaleDateString('tr-TR')
    ]);
    
    // Tablo başlıkları
    const headers = [
      ['ID', 'Bağışçı Adı', 'Email', 'Bağış Türü', 'Miktar', 'Durum', 'Tarih']
    ];
    
    doc.autoTable({
      head: headers,
      body: tableData,
      startY: 20,
    });
    
    // PDF'i indir
    doc.save('bağışlar-listesi.pdf');
    
    setDropdownOpen(false);
  };

  const handleExportExcel = () => {
    // Excel veri formatı
    const excelData = donations.map(donation => ({
      'ID': donation.id,
      'Bağışçı Adı': donation.donor_name,
      'Email': donation.donor_email,
      'Telefon': donation.donor_phone,
      'Bağış Türü': donation.donation_categori,
      'Miktar (₺)': parseFloat(donation.amount as string),
      'Durum': getStatusText(donation.payment_status),
      'Ödeme Yöntemi': donation.payment_method || '-',
      'Tarih': new Date(donation.created_at).toLocaleDateString('tr-TR')
    }));
    
    // Çalışma kitabı oluştur
    const worksheet = XLSX.utils.json_to_sheet(excelData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Bağışlar');
    
    // Excel dosyası olarak indir
    const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    const data = new Blob([excelBuffer], { type: 'application/octet-stream' });
    saveAs(data, 'bağışlar-listesi.xlsx');
    
    setDropdownOpen(false);
  };
  
  // Durum metni getir
  const getStatusText = (status: string): string => {
    switch(status) {
      case 'pending': return 'Beklemede';
      case 'completed': return 'Tamamlandı';
      case 'failed': return 'Başarısız';
      default: return status;
    }
  };
  
  // Durum sınıfı getir
  const getStatusClass = (status: string): string => {
    switch(status) {
      case 'pending': return 'bg-yellow-100 text-yellow-800';
      case 'completed': return 'bg-green-100 text-green-800';
      case 'failed': return 'bg-red-100 text-red-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  // Sayı formatı
  const formatAmount = (amount: number | string): string => {
    if (typeof amount === 'string') {
      return parseFloat(amount).toFixed(2);
    }
    return amount.toFixed(2);
  };

  return (
    <div className="bg-white shadow-md rounded-lg overflow-hidden">
      <div className="p-4 border-b border-gray-200 flex flex-col md:flex-row justify-between md:items-center space-y-3 md:space-y-0">
        <h2 className="text-xl font-semibold text-gray-800">Yapılan Bağışlar</h2>
        
        <div className="flex flex-col sm:flex-row gap-2">
          <form onSubmit={handleSearch} className="flex">
            <input
              type="text"
              placeholder="Ara..."
              className="border rounded-l px-3 py-2 text-sm focus:outline-none"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
            <button 
              type="submit"
              className="bg-blue-600 text-white px-3 py-2 rounded-r"
              aria-label="Ara"
            >
              <FaSearch className="h-4 w-4" />
            </button>
          </form>
          
          <div className="relative">
            <button
              onClick={toggleDropdown}
              className="bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center"
            >
              <FaDownload className="h-4 w-4 mr-1" />
              <span>Dışa Aktar</span>
            </button>
            
            {dropdownOpen && (
              <div className="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-10">
                <button
                  onClick={handleExportPDF}
                  className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                >
                  <FaFilePdf className="h-4 w-4 mr-2 text-red-500" />
                  PDF olarak indir
                </button>
                <button
                  onClick={handleExportExcel}
                  className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                >
                  <FaFileExcel className="h-4 w-4 mr-2 text-green-600" />
                  Excel olarak indir
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
      
      <div className="p-4 border-b border-gray-200 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="status-filter">Durum</label>
          <select
            id="status-filter"
            className="border rounded w-full px-3 py-2 text-sm focus:outline-none"
            value={selectedStatus}
            onChange={(e) => {
              setSelectedStatus(e.target.value);
              setPagination({...pagination, currentPage: 1});
            }}
            title="Durum Filtresi"
          >
            <option value="">Tümü</option>
            <option value="completed">Tamamlandı</option>
            <option value="pending">Beklemede</option>
            <option value="failed">Başarısız</option>
          </select>
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="type-filter">Bağış Türü</label>
          <select
            id="type-filter"
            className="border rounded w-full px-3 py-2 text-sm focus:outline-none"
            value={selectedType}
            onChange={(e) => {
              setSelectedType(e.target.value);
              setPagination({...pagination, currentPage: 1});
            }}
            title="Bağış Türü Filtresi"
          >
            <option value="">Tümü</option>
            {filters.donationTypes.map((type, index) => (
              <option key={index} value={type.donation_type}>
                {type.donation_type}
              </option>
            ))}
          </select>
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="start-date">Başlangıç Tarihi</label>
          <input
            id="start-date"
            type="date"
            className="border rounded w-full px-3 py-2 text-sm focus:outline-none"
            value={dateRange.startDate}
            onChange={(e) => {
              setDateRange({...dateRange, startDate: e.target.value});
              setPagination({...pagination, currentPage: 1});
            }}
            title="Başlangıç Tarihi"
          />
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1" htmlFor="end-date">Bitiş Tarihi</label>
          <input
            id="end-date"
            type="date"
            className="border rounded w-full px-3 py-2 text-sm focus:outline-none"
            value={dateRange.endDate}
            onChange={(e) => {
              setDateRange({...dateRange, endDate: e.target.value});
              setPagination({...pagination, currentPage: 1});
            }}
            title="Bitiş Tarihi"
          />
        </div>
      </div>
      
      <div className="overflow-x-auto">
        {loading ? (
          <div className="flex justify-center items-center p-8">
            <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
          </div>
        ) : (
          <table className="min-w-full divide-y divide-gray-200">
            <thead className="bg-gray-50">
              <tr>
                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  ID
                </th>
                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Bağışçı Bilgileri
                </th>
                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Bağış Türü
                </th>
                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Miktar
                </th>
                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Durum
                </th>
                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tarih
                </th>
                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  İşlemler
                </th>
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              {donations.length > 0 ? (
                donations.map((donation) => (
                  <tr key={donation.id}>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {donation.id}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm font-medium text-gray-900">{donation.donor_name}</div>
                      <div className="text-sm text-gray-500">{donation.donor_email}</div>
                      {donation.donor_phone && (
                        <div className="text-sm text-gray-500">{donation.donor_phone}</div>
                      )}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {donation.donation_categori}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                      ₺{formatAmount(donation.amount)}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(donation.payment_status)}`}>
                        {getStatusText(donation.payment_status)}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {new Date(donation.created_at).toLocaleDateString('tr-TR')}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <Link 
                        href={`/dashboard/donations/${donation.id}`}
                        className="text-blue-600 hover:text-blue-900 flex items-center"
                        aria-label={`${donation.donor_name} bağışının detaylarını görüntüle`}
                      >
                        <FaEye className="h-4 w-4 mr-1" />
                        Görüntüle
                      </Link>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={7} className="px-6 py-4 text-center text-sm text-gray-500">
                    Kayıt bulunamadı
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        )}
      </div>
      
      {/* Sayfalama */}
      {pagination.totalPages > 1 && (
        <div className="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div className="flex-1 flex justify-between sm:hidden">
            <button
              onClick={() => handlePageChange(pagination.currentPage - 1)}
              disabled={pagination.currentPage === 1}
              className={`relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
                pagination.currentPage === 1
                  ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                  : 'bg-white text-gray-700 hover:bg-gray-50'
              }`}
              aria-label="Önceki sayfa"
            >
              Önceki
            </button>
            <button
              onClick={() => handlePageChange(pagination.currentPage + 1)}
              disabled={pagination.currentPage === pagination.totalPages}
              className={`ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
                pagination.currentPage === pagination.totalPages
                  ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                  : 'bg-white text-gray-700 hover:bg-gray-50'
              }`}
              aria-label="Sonraki sayfa"
            >
              Sonraki
            </button>
          </div>
          <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p className="text-sm text-gray-700">
                Toplam <span className="font-medium">{pagination.totalRecords}</span> kayıt,
                <span className="font-medium"> {pagination.currentPage}</span> / {pagination.totalPages} sayfa
              </p>
            </div>
            <div>
              <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  onClick={() => handlePageChange(pagination.currentPage - 1)}
                  disabled={pagination.currentPage === 1}
                  className={`relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium ${
                    pagination.currentPage === 1
                      ? 'text-gray-300 cursor-not-allowed'
                      : 'text-gray-500 hover:bg-gray-50'
                  }`}
                  aria-label="Önceki sayfa"
                >
                  <span className="sr-only">Önceki</span>
                  &larr;
                </button>
                
                {/* Sayfa numaraları */}
                {Array.from({ length: pagination.totalPages }, (_, i) => i + 1)
                  .filter(page => {
                    // Görüntülenecek sayfa numaralarını sınırla
                    const current = pagination.currentPage;
                    return (
                      page === 1 ||
                      page === pagination.totalPages ||
                      (page >= current - 1 && page <= current + 1)
                    );
                  })
                  .map((page, i, filteredPages) => {
                    // Sayfa numaraları arasında boşluk olduğunda ... göster
                    const prevPage = filteredPages[i - 1];
                    const showEllipsis = prevPage && page - prevPage > 1;
                    
                    return (
                      <div key={page}>
                        {showEllipsis && (
                          <span className="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            ...
                          </span>
                        )}
                        <button
                          onClick={() => handlePageChange(page)}
                          className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                            page === pagination.currentPage
                              ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                          }`}
                          aria-label={`${page}. sayfaya git`}
                          aria-current={page === pagination.currentPage ? "page" : undefined}
                        >
                          {page}
                        </button>
                      </div>
                    );
                  })}
                
                <button
                  onClick={() => handlePageChange(pagination.currentPage + 1)}
                  disabled={pagination.currentPage === pagination.totalPages}
                  className={`relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium ${
                    pagination.currentPage === pagination.totalPages
                      ? 'text-gray-300 cursor-not-allowed'
                      : 'text-gray-500 hover:bg-gray-50'
                  }`}
                  aria-label="Sonraki sayfa"
                >
                  <span className="sr-only">Sonraki</span>
                  &rarr;
                </button>
              </nav>
            </div>
          </div>
        </div>
      )}
    </div>
  );
} 