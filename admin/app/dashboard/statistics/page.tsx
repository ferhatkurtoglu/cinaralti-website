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
import { useState } from 'react';
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
  
  // Örnek veriler - API'den gerçek veriler alınabilir
  const trendData = {
    daily: {
      labels: ['1 Haz', '2 Haz', '3 Haz', '4 Haz', '5 Haz', '6 Haz', '7 Haz'],
      values: [25000, 32000, 18000, 42000, 35000, 50000, 38000]
    },
    weekly: {
      labels: ['1. Hafta', '2. Hafta', '3. Hafta', '4. Hafta', '5. Hafta', '6. Hafta', '7. Hafta', '8. Hafta'],
      values: [105000, 125000, 145000, 165000, 185000, 210000, 245000, 270000]
    },
    monthly: {
      labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
      values: [350000, 420000, 380000, 450000, 520000, 480000, 550000, 600000, 580000, 620000, 680000, 750000]
    },
    yearly: {
      labels: ['2019', '2020', '2021', '2022', '2023'],
      values: [2500000, 3800000, 4200000, 5500000, 6800000]
    }
  };
  
  const categoryData = {
    labels: ['Acil Yardım', 'Yetim', 'Eğitim', 'Kurban', 'Genel', 'Projeler'],
    values: [35, 20, 15, 10, 10, 10]
  };
  
  const donorTypeData = {
    labels: ['Bireysel', 'Kurumsal', 'Grup'],
    values: [75, 20, 5]
  };

  // Bağış tiplerine göre dağılım için veri
  const donationTypeData = {
    labels: ['Genel Bağış', 'Zekat', 'Filistin Yardımı', 'Yetim Projesi', 'Kurban Bağışı', 'Afrika Bağışı', 'Kuran Talebelerinin İhtiyaçları', 'Bina Satın Alma'],
    values: [25, 20, 18, 12, 10, 8, 5, 2],
    amounts: [1250000, 1000000, 900000, 600000, 500000, 400000, 250000, 100000]
  };
  
  // Şehir bazlı bağış dağılımı için veri
  const cityData = {
    labels: ['İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Antalya', 'Konya', 'Adana', 'Kayseri', 'Gaziantep', 'Trabzon'],
    values: [35, 15, 12, 8, 7, 6, 5, 5, 4, 3],
    amounts: [1750000, 750000, 600000, 400000, 350000, 300000, 250000, 250000, 200000, 150000]
  };

  // Aktif periyoda göre trend verilerini al
  const activeTrendData = trendData[period as keyof typeof trendData];

  const trendChartData = {
    labels: activeTrendData.labels,
    datasets: [
      {
        label: 'Bağış Miktarı (₺)',
        data: activeTrendData.values,
        backgroundColor: 'rgba(76, 175, 80, 0.2)',
        borderColor: '#4CAF50',
        borderWidth: 2,
        tension: 0.4,
        fill: true
      }
    ]
  };

  const categoryChartData = {
    labels: categoryData.labels,
    datasets: [
      {
        data: categoryData.values,
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

  const donorTypeChartData = {
    labels: donorTypeData.labels,
    datasets: [
      {
        data: donorTypeData.values,
        backgroundColor: [
          '#4CAF50',
          '#FF9800',
          '#2196F3'
        ],
        borderWidth: 1
      }
    ]
  };

  const donationTypeChartData = {
    labels: donationTypeData.labels,
    datasets: [
      {
        data: donationTypeData.values,
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
  
  const cityChartData = {
    labels: cityData.labels,
    datasets: [
      {
        label: 'Şehir Bazlı Bağış Miktarı',
        data: cityData.amounts,
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

  const handleExport = (format: string) => {
    alert(`Veriler ${format} formatında dışa aktarılacak`);
    // TODO: Gerçek dışa aktarma işlemi eklenecek
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
          
          <div className="dropdown relative">
            <button className="btn btn-primary flex items-center">
              <FaDownload className="mr-2" />
              Dışa Aktar
            </button>
            <div className="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden">
              <button
                onClick={() => handleExport('excel')}
                className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                Excel
              </button>
              <button
                onClick={() => handleExport('pdf')}
                className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                PDF
              </button>
              <button
                onClick={() => handleExport('csv')}
                className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                CSV
              </button>
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
          className={`px-4 py-2 font-medium ${activeTab === 'donor' ? 'text-primary border-b-2 border-primary' : 'text-gray-500'}`}
          onClick={() => setActiveTab('donor')}
        >
          <FaChartBar className="inline mr-2" />
          Bağışçı Tipi
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
                  <p className="text-lg font-bold">{activeTrendData.values.reduce((a, b) => a + b, 0).toLocaleString('tr-TR')}₺</p>
                </div>
                <div>
                  <p className="text-sm text-gray-500">Ortalama</p>
                  <p className="text-lg font-bold">{Math.round(activeTrendData.values.reduce((a, b) => a + b, 0) / activeTrendData.values.length).toLocaleString('tr-TR')}₺</p>
                </div>
                <div>
                  <p className="text-sm text-gray-500">En Yüksek</p>
                  <p className="text-lg font-bold">{Math.max(...activeTrendData.values).toLocaleString('tr-TR')}₺</p>
                </div>
                <div>
                  <p className="text-sm text-gray-500">En Düşük</p>
                  <p className="text-lg font-bold">{Math.min(...activeTrendData.values).toLocaleString('tr-TR')}₺</p>
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
                            return context.label + ': %' + context.parsed;
                          }
                        }
                      }
                    }
                  }}
                />
              </div>
              <div>
                <table className="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yüzde</th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Miktar</th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {categoryData.labels.map((label, index) => (
                      <tr key={index}>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="flex items-center">
                            <div className="h-3 w-3 rounded-full mr-2" style={{ backgroundColor: (categoryChartData.datasets[0].backgroundColor as string[])[index] }}></div>
                            <div className="text-sm font-medium text-gray-900">{label}</div>
                          </div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">%{categoryData.values[index]}</div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">{(categoryData.values[index] * 50000).toLocaleString('tr-TR')}₺</div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
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
                            return context.label + ': %' + context.parsed;
                          }
                        }
                      }
                    }
                  }}
                />
              </div>
              <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bağış Tipi</th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yüzde</th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Miktar</th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {donationTypeData.labels.map((label, index) => (
                      <tr key={index}>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="flex items-center">
                            <div className="h-3 w-3 rounded-full mr-2" style={{ backgroundColor: (donationTypeChartData.datasets[0].backgroundColor as string[])[index] }}></div>
                            <div className="text-sm font-medium text-gray-900">{label}</div>
                          </div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">%{donationTypeData.values[index]}</div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">{donationTypeData.amounts[index].toLocaleString('tr-TR')}₺</div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}
        
        {/* Bağışçı Tipi */}
        {activeTab === 'donor' && (
          <div className="card">
            <div className="card-header">
              <h2 className="card-title">Bağışçı Tiplerine Göre Dağılım</h2>
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
              <div className="h-80 flex items-center justify-center">
                <Pie 
                  data={donorTypeChartData}
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
                            return context.label + ': %' + context.parsed;
                          }
                        }
                      }
                    }
                  }}
                />
              </div>
              <div>
                <table className="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bağışçı Tipi</th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yüzde</th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Miktar</th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {donorTypeData.labels.map((label, index) => (
                      <tr key={index}>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="flex items-center">
                            <div className="h-3 w-3 rounded-full mr-2" style={{ backgroundColor: (donorTypeChartData.datasets[0].backgroundColor as string[])[index] }}></div>
                            <div className="text-sm font-medium text-gray-900">{label}</div>
                          </div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">%{donorTypeData.values[index]}</div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">{(donorTypeData.values[index] * 50000).toLocaleString('tr-TR')}₺</div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
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
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
              <div className="h-80 flex items-center justify-center">
                <Bar 
                  data={cityChartData}
                  options={{
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                      legend: {
                        position: 'top',
                      },
                      tooltip: {
                        callbacks: {
                          label: function(context) {
                            return context.parsed.y.toLocaleString('tr-TR') + '₺';
                          }
                        }
                      }
                    },
                    scales: {
                      y: {
                        beginAtZero: true,
                        ticks: {
                          callback: function(value) {
                            return value.toLocaleString('tr-TR') + '₺';
                          }
                        }
                      }
                    }
                  }}
                />
              </div>
              <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Şehir</th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yüzde</th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Miktar</th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {cityData.labels.map((label, index) => (
                      <tr key={index}>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="flex items-center">
                            <div className="h-3 w-3 rounded-full mr-2" style={{ backgroundColor: (cityChartData.datasets[0].backgroundColor as string[])[index] }}></div>
                            <div className="text-sm font-medium text-gray-900">{label}</div>
                          </div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">%{cityData.values[index]}</div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-sm text-gray-900">{cityData.amounts[index].toLocaleString('tr-TR')}₺</div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
} 