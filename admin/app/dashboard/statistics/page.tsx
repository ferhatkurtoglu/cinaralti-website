'use client';

import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Title,
    Tooltip
} from 'chart.js';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import { useEffect, useRef, useState } from 'react';
import { Bar, Line, Pie } from 'react-chartjs-2';
import {
    FaCalendarAlt,
    FaChartBar,
    FaChartLine,
    FaChartPie,
    FaDownload,
    FaHandHoldingHeart,
    FaMapMarkerAlt
} from 'react-icons/fa';
import * as XLSX from 'xlsx';

// Chart.js bileşenlerini kaydet
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  ArcElement,
  Title,
  Tooltip,
  Legend
);

export default function Statistics() {
  const [period, setPeriod] = useState('monthly');
  const [activeTab, setActiveTab] = useState('trend');
  const [dropdownOpen, setDropdownOpen] = useState<boolean>(false);
  const dropdownRef = useRef<HTMLDivElement>(null);
  const [statsData, setStatsData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  
  // API'den verileri al
  useEffect(() => {
    const fetchStats = async () => {
      try {
        setLoading(true);
        const response = await fetch(`/api/statistics?period=${period}`);
        const data = await response.json();
        setStatsData(data);
      } catch (error) {
        console.error('İstatistikler yüklenirken hata:', error);
      } finally {
        setLoading(false);
      }
    };
    
    fetchStats();
  }, [period]);
  
  // Dropdown dışına tıklandığında kapat
  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setDropdownOpen(false);
      }
    }
    
    document.addEventListener("mousedown", handleClickOutside);
    
    // Cleanup
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  const toggleDropdown = () => {
    setDropdownOpen(!dropdownOpen);
  };

  // API'den gelen verileri işle
  const trendData = statsData?.trend || [];
  const categoryData = statsData?.categories || [];
  const cityData = statsData?.cities || [];
  const donationTypeData = statsData?.donationTypes || [];
  const generalStats = statsData?.general || {};
  
  // Veriler yüklenene kadar loading göster
  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-primary"></div>
      </div>
    );
  }
  
  // Trend verilerini grafik için hazırla
  const trendChartData = {
    labels: trendData.map((item: any) => item.period).reverse(),
    datasets: [
      {
        label: 'Bağış Miktarı (₺)',
        data: trendData.map((item: any) => parseFloat(item.total_amount)).reverse(),
        backgroundColor: 'rgba(76, 175, 80, 0.2)',
        borderColor: '#4CAF50',
        borderWidth: 2,
        tension: 0.4,
        fill: true
      }
    ]
  };
  
  // Kategori verilerini grafik için hazırla
  const categoryChartData = {
    labels: categoryData.map((item: any) => item.donation_category),
    datasets: [
      {
        data: categoryData.map((item: any) => parseFloat(item.total_amount)),
        backgroundColor: [
          '#4CAF50',
          '#2196F3',
          '#FF9800',
          '#9C27B0',
          '#607D8B',
          '#795548'
        ],
        borderWidth: 1
      }
    ]
  };
  
  // Şehir verilerini grafik için hazırla
  const cityChartData = {
    labels: cityData.map((item: any) => item.city),
    datasets: [
      {
        label: 'Şehir Bazlı Bağış Miktarı',
        data: cityData.map((item: any) => parseFloat(item.total_amount)),
        backgroundColor: [
          '#4CAF50',
          '#2196F3',
          '#FF5722',
          '#9C27B0',
          '#795548',
          '#FF9800',
          '#607D8B',
          '#3F51B5',
          '#FFC107',
          '#E91E63'
        ],
        borderWidth: 1
      }
    ]
  };
  
  // Bağış türü verilerini grafik için hazırla
  const donationTypeChartData = {
    labels: donationTypeData.map((item: any) => item.donation_option),
    datasets: [
      {
        data: donationTypeData.map((item: any) => parseFloat(item.total_amount)),
        backgroundColor: [
          '#4CAF50',
          '#2196F3',
          '#FF5722',
          '#9C27B0',
          '#795548',
          '#FF9800',
          '#607D8B',
          '#3F51B5'
        ],
        borderWidth: 1
      }
    ]
  };

  const handleExport = (format: string) => {
    const prepareChartData = () => {
      let data = [];
      
      if (activeTab === 'trend') {
        // Trend verileri
        data = trendData.map((item: any) => ({
          'Dönem': item.period,
          'Bağış Miktarı (₺)': parseFloat(item.total_amount || 0),
          'Bağış Sayısı': parseInt(item.donation_count || 0),
        }));
      } else if (activeTab === 'category') {
        // Kategori verileri
        data = categoryData.map((item: any) => ({
          'Kategori': item.donation_category,
          'Toplam Tutar (₺)': parseFloat(item.total_amount || 0),
          'Bağış Sayısı': parseInt(item.donation_count || 0),
          'Ortalama (₺)': parseFloat(item.avg_amount || 0),
        }));
      } else if (activeTab === 'donation-type') {
        // Bağış tipine göre veriler
        data = donationTypeData.map((item: any) => ({
          'Bağış Tipi': item.donation_option,
          'Toplam Tutar (₺)': parseFloat(item.total_amount || 0),
          'Bağış Sayısı': parseInt(item.donation_count || 0),
          'Ortalama (₺)': parseFloat(item.avg_amount || 0),
        }));
      } else if (activeTab === 'city') {
        // Şehir verileri
        data = cityData.map((item: any) => ({
          'Şehir': item.city,
          'Toplam Tutar (₺)': parseFloat(item.total_amount || 0),
          'Bağış Sayısı': parseInt(item.donation_count || 0),
          'Ortalama (₺)': parseFloat(item.avg_amount || 0),
        }));
      }
      
      return data;
    };
    
    const data = prepareChartData();
    
    // Başlık belirleme
    const tabTitle = 
      activeTab === 'trend' ? 'Bağış_Trendi' :
      activeTab === 'category' ? 'Kategoriye_Gore_Bağışlar' :
      activeTab === 'donation-type' ? 'Bağış_Tipleri' : 'Şehir_Bazlı_Bağışlar';
    
    if (format === 'excel') {
      try {
        const worksheet = XLSX.utils.json_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        
        XLSX.utils.book_append_sheet(workbook, worksheet, tabTitle);
        
        // Sütun genişliklerini ayarla
        const maxWidth = data.reduce((w, r) => {
          const firstKey = Object.keys(r)[0];
          return Math.max(w, String(r[firstKey]).length);
        }, 10);
        
        worksheet['!cols'] = [
          { wch: maxWidth }, // İlk sütun
          { wch: 15 }, // İkinci sütun (yüzde veya miktar)
          { wch: 15 }, // Üçüncü sütun (varsa)
        ];
        
        // Binary string oluştur
        const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
        
        // Blob oluştur
        const blob = new Blob([excelBuffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        
        // Dosyayı indir
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `Bağış_İstatistikleri_${tabTitle}.xlsx`;
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
        
        // Başlık belirleme
        const title = 
          activeTab === 'trend' ? 'Bağış Trendi' :
          activeTab === 'category' ? 'Kategoriye Göre Bağışlar' :
          activeTab === 'donation-type' ? 'Bağış Tipleri' : 'Şehir Bazlı Bağışlar';
        
        // Başlık
        doc.setFontSize(16);
        doc.text(`Bağış İstatistikleri - ${title}`, 14, 15);
        doc.setFontSize(10);
        doc.text(`Oluşturulma Tarihi: ${new Date().toLocaleString('tr-TR')}`, 14, 22);
        doc.text(`Periyot: ${
          period === 'daily' ? 'Günlük' :
          period === 'weekly' ? 'Haftalık' :
          period === 'monthly' ? 'Aylık' : 'Yıllık'
        }`, 14, 28);
        
        // Tablo için kolon başlıkları
        const columns = Object.keys(data[0]);
        
        // Tablo
        (doc as any).autoTable({
          head: [columns],
          body: data.map(row => Object.values(row)),
          startY: 35,
          styles: { fontSize: 8, cellPadding: 2 },
          headStyles: { fillColor: [76, 175, 80] }
        });
        
        // Blob olarak kaydet ve indir
        const pdfBlob = doc.output('blob');
        const url = URL.createObjectURL(pdfBlob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `Bağış_İstatistikleri_${tabTitle}.pdf`;
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
    }
  };

  return (
    <div>
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 className="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Bağış İstatistikleri</h1>
        
        <div className="flex flex-col sm:flex-row items-start sm:items-center gap-4">
          <div className="flex items-center">
            <FaCalendarAlt className="text-gray-500 mr-2" />
            <select
              value={period}
              onChange={(e) => setPeriod(e.target.value)}
              className="select-field w-auto"
              aria-label="Zaman periyodu seçin"
            >
              <option value="daily">Günlük</option>
              <option value="weekly">Haftalık</option>
              <option value="monthly">Aylık</option>
              <option value="yearly">Yıllık</option>
            </select>
          </div>
          
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
              </div>
            )}
          </div>
        </div>
      </div>
      
      {/* Genel İstatistik Kartları */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div className="card">
          <div className="card-body">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-green-100 text-green-600">
                <FaHandHoldingHeart className="text-xl" />
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-500">Toplam Bağış</p>
                <p className="text-2xl font-bold text-gray-900">
                  {generalStats.total_donations?.toLocaleString('tr-TR') || '0'}
                </p>
              </div>
            </div>
          </div>
        </div>
        
        <div className="card">
          <div className="card-body">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-blue-100 text-blue-600">
                <FaChartBar className="text-xl" />
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-500">Toplam Tutar</p>
                <p className="text-2xl font-bold text-gray-900">
                  {parseFloat(generalStats.total_amount || 0).toLocaleString('tr-TR')}₺
                </p>
              </div>
            </div>
          </div>
        </div>
        
        <div className="card">
          <div className="card-body">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-orange-100 text-orange-600">
                <FaChartLine className="text-xl" />
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-500">Ortalama Bağış</p>
                <p className="text-2xl font-bold text-gray-900">
                  {parseFloat(generalStats.avg_amount || 0).toLocaleString('tr-TR')}₺
                </p>
              </div>
            </div>
          </div>
        </div>
        
        <div className="card">
          <div className="card-body">
            <div className="flex items-center">
              <div className="p-3 rounded-full bg-purple-100 text-purple-600">
                <FaChartPie className="text-xl" />
              </div>
              <div className="ml-4">
                <p className="text-sm font-medium text-gray-500">Benzersiz Bağışçı</p>
                <p className="text-2xl font-bold text-gray-900">
                  {generalStats.unique_donors?.toLocaleString('tr-TR') || '0'}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      {/* Sekme Menüsü */}
      <div className="flex mb-6 border-b border-gray-200 overflow-x-auto">
        <button
          className={`px-4 py-2 font-medium ${activeTab === 'trend' ? 'text-primary border-b-2 border-primary' : 'text-gray-500'}`}
          onClick={() => setActiveTab('trend')}
        >
          <FaChartLine className="inline mr-2" />
          Bağış Trendi
        </button>
        <button
          className={`px-4 py-2 font-medium ${activeTab === 'category' ? 'text-primary border-b-2 border-primary' : 'text-gray-500'}`}
          onClick={() => setActiveTab('category')}
        >
          <FaChartPie className="inline mr-2" />
          Kategori Analizi
        </button>
        <button
          className={`px-4 py-2 font-medium ${activeTab === 'donation-type' ? 'text-primary border-b-2 border-primary' : 'text-gray-500'}`}
          onClick={() => setActiveTab('donation-type')}
        >
          <FaHandHoldingHeart className="inline mr-2" />
          Bağış Tipi Analizi
        </button>
        <button
          className={`px-4 py-2 font-medium ${activeTab === 'city' ? 'text-primary border-b-2 border-primary' : 'text-gray-500'}`}
          onClick={() => setActiveTab('city')}
        >
          <FaMapMarkerAlt className="inline mr-2" />
          Şehir Analizi
        </button>
      </div>
      
      {/* Tab İçerikleri */}
      <div className="grid grid-cols-1 gap-6">
        {/* Bağış Trendi */}
        {activeTab === 'trend' && (
          <div className="card">
            <div className="card-header">
              <h2 className="card-title">Bağış Trendi - {period === 'daily' ? 'Günlük' : period === 'weekly' ? 'Haftalık' : period === 'monthly' ? 'Aylık' : 'Yıllık'}</h2>
            </div>
            <div className="p-4">
              <div className="h-96">
                <Line 
                  data={trendChartData}
                  options={{
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                      y: {
                        beginAtZero: true,
                        ticks: {
                          callback: function(value) {
                            return value.toLocaleString('tr-TR') + '₺';
                          }
                        }
                      }
                    },
                    plugins: {
                      tooltip: {
                        callbacks: {
                          label: function(context) {
                            return context.parsed.y.toLocaleString('tr-TR') + '₺';
                          }
                        }
                      }
                    }
                  }}
                />
              </div>
            </div>
            <div className="p-4 border-t border-gray-100">
              <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                  <p className="text-sm text-gray-500">Toplam Bağış</p>
                  <p className="text-lg font-bold">
                    {trendData.reduce((sum: number, item: any) => sum + parseFloat(item.total_amount || 0), 0).toLocaleString('tr-TR')}₺
                  </p>
                </div>
                <div>
                  <p className="text-sm text-gray-500">Ortalama</p>
                  <p className="text-lg font-bold">
                    {trendData.length > 0 ? 
                      Math.round(trendData.reduce((sum: number, item: any) => sum + parseFloat(item.total_amount || 0), 0) / trendData.length).toLocaleString('tr-TR') : 0
                    }₺
                  </p>
                </div>
                <div>
                  <p className="text-sm text-gray-500">En Yüksek</p>
                  <p className="text-lg font-bold">
                    {trendData.length > 0 ? 
                      Math.max(...trendData.map((item: any) => parseFloat(item.total_amount || 0))).toLocaleString('tr-TR') : 0
                    }₺
                  </p>
                </div>
                <div>
                  <p className="text-sm text-gray-500">En Düşük</p>
                  <p className="text-lg font-bold">
                    {trendData.length > 0 ? 
                      Math.min(...trendData.map((item: any) => parseFloat(item.total_amount || 0))).toLocaleString('tr-TR') : 0
                    }₺
                  </p>
                </div>
              </div>
            </div>
          </div>
        )}
        
        {/* Kategori Analizi */}
        {activeTab === 'category' && (
          <div className="card">
            <div className="card-header">
              <h2 className="card-title">Kategorilere Göre Bağış Dağılımı</h2>
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
              <div className="h-80 flex items-center justify-center">
                <Pie 
                  data={categoryChartData}
                  options={{
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                      legend: {
                        position: 'right',
                      },
                      tooltip: {
                        callbacks: {
                          label: function(context) {
                            return context.label + ': ' + context.parsed.toLocaleString('tr-TR') + '₺';
                          }
                        }
                      }
                    }
                  }}
                />
              </div>
              <div className="space-y-4">
                <h3 className="text-lg font-semibold">Kategori Detayları</h3>
                <div className="space-y-2">
                  {categoryData.map((item: any, index: number) => (
                    <div key={index} className="flex justify-between items-center p-3 bg-gray-50 rounded">
                      <span className="font-medium">{item.donation_category}</span>
                      <span className="text-primary font-bold">
                        {parseFloat(item.total_amount || 0).toLocaleString('tr-TR')}₺
                      </span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        )}
        
        {/* Bağış Tipi Analizi */}
        {activeTab === 'donation-type' && (
          <div className="card">
            <div className="card-header">
              <h2 className="card-title">Bağış Tiplerine Göre Dağılım</h2>
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
              <div className="h-80 flex items-center justify-center">
                <Pie 
                  data={donationTypeChartData}
                  options={{
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                      legend: {
                        position: 'right',
                      },
                      tooltip: {
                        callbacks: {
                          label: function(context) {
                            return context.label + ': ' + context.parsed.toLocaleString('tr-TR') + '₺';
                          }
                        }
                      }
                    }
                  }}
                />
              </div>
              <div className="space-y-4">
                <h3 className="text-lg font-semibold">Bağış Tipi Detayları</h3>
                <div className="space-y-2">
                  {donationTypeData.map((item: any, index: number) => (
                    <div key={index} className="flex justify-between items-center p-3 bg-gray-50 rounded">
                      <span className="font-medium">{item.donation_option}</span>
                      <span className="text-primary font-bold">
                        {parseFloat(item.total_amount || 0).toLocaleString('tr-TR')}₺
                      </span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        )}
        
        {/* Şehir Analizi */}
        {activeTab === 'city' && (
          <div className="card">
            <div className="card-header">
              <h2 className="card-title">Şehirlere Göre Bağış Dağılımı</h2>
            </div>
            <div className="p-4">
              <div className="h-96">
                <Bar 
                  data={cityChartData}
                  options={{
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                      y: {
                        beginAtZero: true,
                        ticks: {
                          callback: function(value) {
                            return value.toLocaleString('tr-TR') + '₺';
                          }
                        }
                      }
                    },
                    plugins: {
                      tooltip: {
                        callbacks: {
                          label: function(context) {
                            return context.parsed.y.toLocaleString('tr-TR') + '₺';
                          }
                        }
                      }
                    }
                  }}
                />
              </div>
            </div>
            <div className="p-4 border-t border-gray-100">
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {cityData.map((item: any, index: number) => (
                  <div key={index} className="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span className="font-medium">{item.city}</span>
                    <span className="text-primary font-bold">
                      {parseFloat(item.total_amount || 0).toLocaleString('tr-TR')}₺
                    </span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
} 