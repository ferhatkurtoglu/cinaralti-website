'use client';

import {
  ArcElement,
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
import { Doughnut, Line } from 'react-chartjs-2';
import {
  FaArrowDown,
  FaArrowUp,
  FaCalendarDay,
  FaDonate,
  FaUsers
} from 'react-icons/fa';

// Chart.js bileşenlerini kaydet
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
);

export default function Dashboard() {
  const [period, setPeriod] = useState('monthly');
  
  // Örnek veriler - API'den gerçek veriler alınabilir
  const summaryData = {
    totalDonation: '4.583.250₺',
    totalDonors: 1245,
    monthlyIncrease: 8.2,
    avgDonation: '3.682₺'
  };

  // Son bağışlar için örnek veri
  const recentDonations = [
    { id: 1, name: 'Ahmet Yılmaz', type: 'Genel Bağış', amount: '1.000₺', date: '2023-06-15', status: 'success' },
    { id: 2, name: 'Mehmet Öz', type: 'Zekat', amount: '500₺', date: '2023-06-14', status: 'success' },
    { id: 3, name: 'Ayşe Demir', type: 'Yetim Projesi', amount: '150₺', date: '2023-06-14', status: 'success' },
    { id: 4, name: 'Fatma Şahin', type: 'Kurban Bağışı', amount: '2.500₺', date: '2023-06-13', status: 'pending' },
    { id: 5, name: 'Ali Can', type: 'Filistin Yardımı', amount: '300₺', date: '2023-06-12', status: 'success' }
  ];

  // Çizgi grafik verileri - API'den gerçek veriler alınabilir
  const lineChartData = {
    labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz'],
    datasets: [
      {
        label: 'Bağış Miktarı (₺)',
        data: [150000, 220000, 180000, 260000, 290000, 350000, 400000],
        borderColor: '#4CAF50',
        backgroundColor: 'rgba(76, 175, 80, 0.1)',
        fill: true,
        tension: 0.4
      }
    ]
  };

  // Pasta grafik verileri - API'den gerçek veriler alınabilir
  const doughnutChartData = {
    labels: ['Acil Yardım', 'Yetim', 'Eğitim', 'Kurban', 'Genel'],
    datasets: [
      {
        data: [35, 20, 15, 10, 20],
        backgroundColor: [
          '#4CAF50',
          '#2196F3',
          '#FF9800',
          '#9C27B0',
          '#607D8B'
        ],
        borderWidth: 0
      }
    ]
  };

  const lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      }
    }
  };

  const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'right' as const,
        labels: {
          boxWidth: 10,
          padding: 15,
          font: {
            size: 12
          }
        }
      }
    },
    cutout: '70%'
  };

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Dashboard</h1>
        <div className="flex items-center space-x-2">
          <FaCalendarDay className="text-gray-500" />
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
      </div>

      {/* Kartlar - Özet Veriler */}
      <div className="dashboard-stats">
        <div className="stat-card">
          <div className="flex items-center mb-2">
            <div className="stat-icon bg-green-100 mr-3">
              <FaDonate className="text-primary" />
            </div>
            <h3 className="stat-title">Toplam Bağış</h3>
          </div>
          <p className="stat-value text-gray-800">{summaryData.totalDonation}</p>
        </div>

        <div className="stat-card">
          <div className="flex items-center mb-2">
            <div className="stat-icon bg-blue-100 mr-3">
              <FaUsers className="text-blue-500" />
            </div>
            <h3 className="stat-title">Toplam Bağışçı</h3>
          </div>
          <p className="stat-value text-gray-800">{summaryData.totalDonors}</p>
        </div>

        <div className="stat-card">
          <div className="flex items-center mb-2">
            <div className="stat-icon bg-green-100 mr-3">
              {summaryData.monthlyIncrease > 0 ? (
                <FaArrowUp className="text-green-500" />
              ) : (
                <FaArrowDown className="text-red-500" />
              )}
            </div>
            <h3 className="stat-title">Aylık Artış</h3>
          </div>
          <p className="stat-value text-gray-800">
            {summaryData.monthlyIncrease}%
            {summaryData.monthlyIncrease > 0 ? (
              <span className="text-green-500 text-sm ml-1">↑</span>
            ) : (
              <span className="text-red-500 text-sm ml-1">↓</span>
            )}
          </p>
        </div>

        <div className="stat-card">
          <div className="flex items-center mb-2">
            <div className="stat-icon bg-purple-100 mr-3">
              <FaDonate className="text-purple-500" />
            </div>
            <h3 className="stat-title">Ortalama Bağış</h3>
          </div>
          <p className="stat-value text-gray-800">{summaryData.avgDonation}</p>
        </div>
      </div>

      {/* Grafikler */}
      <div className="dashboard-grid">
        <div className="chart-container">
          <h2 className="text-lg font-semibold text-gray-800 mb-4">Bağış Trendi</h2>
          <div className="h-64">
            <Line data={lineChartData} options={lineOptions} />
          </div>
        </div>
        
        <div className="chart-container">
          <h2 className="text-lg font-semibold text-gray-800 mb-4">Kategori Dağılımı</h2>
          <div className="h-64">
            <Doughnut data={doughnutChartData} options={doughnutOptions} />
          </div>
        </div>
      </div>

      {/* Son Bağışlar */}
      <div className="card mt-6">
        <div className="card-header">
          <h2 className="card-title">Son Bağışlar</h2>
          <a href="/dashboard/donations" className="text-primary hover:underline">Tümünü Gör</a>
        </div>
        <div className="table-container">
          <table className="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Bağışçı</th>
                <th>Bağış Türü</th>
                <th>Miktar</th>
                <th>Tarih</th>
                <th>Durum</th>
              </tr>
            </thead>
            <tbody>
              {recentDonations.map(donation => (
                <tr key={donation.id}>
                  <td>#{donation.id}</td>
                  <td>{donation.name}</td>
                  <td>{donation.type}</td>
                  <td className="font-medium">{donation.amount}</td>
                  <td>{new Date(donation.date).toLocaleDateString('tr-TR')}</td>
                  <td>
                    <span className={`badge badge-${donation.status === 'success' ? 'success' : 'warning'}`}>
                      {donation.status === 'success' ? 'Tamamlandı' : 'Bekliyor'}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
} 