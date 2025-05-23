'use client';

import jsPDF from 'jspdf';
import 'jspdf-autotable';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import { FaArrowLeft, FaFilePdf } from 'react-icons/fa';

interface DonationDetail {
  id: number;
  donor_name: string;
  donor_email: string;
  donor_phone: string;
  donor_type: string;
  city?: string;
  donation_categori: string;
  amount: number | string;
  payment_status: string;
  payment_method?: string;
  created_at: string;
  notes?: string;
}

export default function DonationDetail({ params }: { params: { id: string } }) {
  const router = useRouter();
  const [donation, setDonation] = useState<DonationDetail | null>(null);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [statusUpdating, setStatusUpdating] = useState<boolean>(false);

  useEffect(() => {
    const fetchDonationDetail = async () => {
      try {
        setLoading(true);
        const response = await fetch(`/api/donations/${params.id}`);
        
        if (!response.ok) {
          if (response.status === 404) {
            setError('Bağış bulunamadı');
          } else {
            setError('Bağış detayları getirilirken bir hata oluştu');
          }
          return;
        }
        
        const data = await response.json();
        setDonation(data);
      } catch (err) {
        setError('Bağış detayları getirilirken bir hata oluştu');
        console.error('Bağış detayları hatası:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchDonationDetail();
  }, [params.id]);

  const handleStatusChange = async (newStatus: string) => {
    if (!donation) return;
    
    try {
      setStatusUpdating(true);
      
      const response = await fetch(`/api/donations/${params.id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ payment_status: newStatus })
      });
      
      if (!response.ok) {
        throw new Error('Durum güncellenirken bir hata oluştu');
      }
      
      const updatedDonation = await response.json();
      setDonation(updatedDonation);
      
    } catch (err) {
      console.error('Durum güncelleme hatası:', err);
      alert('Bağış durumu güncellenirken bir hata oluştu');
    } finally {
      setStatusUpdating(false);
    }
  };

  const getStatusText = (status: string): string => {
    switch(status) {
      case 'pending': return 'Beklemede';
      case 'completed': return 'Tamamlandı';
      case 'failed': return 'Başarısız';
      default: return status;
    }
  };
  
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

  const handlePrintReceipt = () => {
    if (!donation) return;
    
    const doc = new jsPDF();
    
    // Başlık
    doc.setFontSize(20);
    doc.text('Bağış Makbuzu', 105, 20, { align: 'center' });
    
    // Bağış Bilgileri
    doc.setFontSize(12);
    doc.text(`Makbuz No: ${donation.id}`, 20, 40);
    doc.text(`Tarih: ${new Date(donation.created_at).toLocaleDateString('tr-TR')}`, 20, 50);
    
    // Bağışçı Bilgileri
    doc.setFontSize(14);
    doc.text('Bağışçı Bilgileri', 20, 70);
    doc.setFontSize(10);
    doc.text(`İsim: ${donation.donor_name}`, 20, 80);
    doc.text(`E-posta: ${donation.donor_email}`, 20, 85);
    if (donation.donor_phone) {
      doc.text(`Telefon: ${donation.donor_phone}`, 20, 90);
    }
    if (donation.city) {
      doc.text(`Şehir: ${donation.city}`, 20, 95);
    }
    
    // Bağış Detayları
    doc.setFontSize(14);
    doc.text('Bağış Detayları', 20, 110);
    doc.setFontSize(10);
    doc.text(`Bağış Türü: ${donation.donation_categori}`, 20, 120);
    doc.text(`Miktar: ${formatAmount(donation.amount)} ₺`, 20, 125);
    if (donation.payment_method) {
      doc.text(`Ödeme Yöntemi: ${donation.payment_method}`, 20, 130);
    }
    doc.text(`Durum: ${getStatusText(donation.payment_status)}`, 20, 135);
    
    // Teşekkür mesajı
    doc.setFontSize(12);
    doc.text('Değerli bağışınız için teşekkür ederiz.', 105, 160, { align: 'center' });
    doc.text('Çınaraltı Derneği', 105, 170, { align: 'center' });
    
    // İndir
    doc.save(`bagis-makbuz-${donation.id}.pdf`);
  };

  if (loading) {
    return (
      <div className="container-fluid px-4">
        <div className="flex justify-center items-center min-h-[70vh]">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>
      </div>
    );
  }

  if (error || !donation) {
    return (
      <div className="container-fluid px-4">
        <div className="mt-4 p-6 bg-white rounded shadow">
          <div className="flex flex-col items-center">
            <h2 className="text-xl font-semibold text-red-600 mb-2">Hata</h2>
            <p className="text-gray-700">{error || 'Bağış bilgileri yüklenemedi'}</p>
            <Link 
              href="/dashboard/donations" 
              className="mt-4 bg-blue-600 text-white px-4 py-2 rounded text-sm flex items-center"
            >
              <FaArrowLeft className="h-4 w-4 mr-1" />
              Bağışlara Dön
            </Link>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="container-fluid px-4">
      <div className="flex justify-between items-center mt-4 mb-4">
        <h1 className="text-2xl font-semibold">Bağış Detayları</h1>
        <div className="flex space-x-2">
          <button 
            onClick={handlePrintReceipt}
            className="bg-blue-600 text-white px-3 py-2 rounded text-sm flex items-center"
          >
            <FaFilePdf className="h-4 w-4 mr-1" />
            Makbuz İndir
          </button>
          <Link 
            href="/dashboard/donations" 
            className="bg-gray-600 text-white px-3 py-2 rounded text-sm flex items-center"
          >
            <FaArrowLeft className="h-4 w-4 mr-1" />
            Bağışlara Dön
          </Link>
        </div>
      </div>
      
      <div className="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div className="p-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h2 className="text-lg font-semibold mb-4 border-b pb-2">Bağış Bilgileri</h2>
              
              <div className="space-y-3">
                <div className="flex justify-between">
                  <span className="text-gray-600">Bağış ID:</span>
                  <span className="font-medium">{donation.id}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Bağış Türü:</span>
                  <span className="font-medium">{donation.donation_categori}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Miktar:</span>
                  <span className="font-medium">₺{formatAmount(donation.amount)}</span>
                </div>
                {donation.payment_method && (
                  <div className="flex justify-between">
                    <span className="text-gray-600">Ödeme Yöntemi:</span>
                    <span>{donation.payment_method}</span>
                  </div>
                )}
                <div className="flex justify-between items-center">
                  <span className="text-gray-600">Durum:</span>
                  <span className={`px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(donation.payment_status)}`}>
                    {getStatusText(donation.payment_status)}
                  </span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Tarih:</span>
                  <span>{new Date(donation.created_at).toLocaleString('tr-TR')}</span>
                </div>
              </div>
              
              {/* Durum Değiştirme */}
              <div className="mt-6 pt-4 border-t">
                <h3 className="text-sm font-semibold mb-2">Durum Değiştir</h3>
                <div className="flex space-x-2">
                  <button
                    onClick={() => handleStatusChange('completed')}
                    disabled={donation.payment_status === 'completed' || statusUpdating}
                    className={`px-3 py-1 text-xs rounded ${
                      donation.payment_status === 'completed'
                        ? 'bg-gray-100 text-gray-500 cursor-not-allowed'
                        : 'bg-green-100 text-green-800 hover:bg-green-200'
                    }`}
                  >
                    Tamamlandı
                  </button>
                  <button
                    onClick={() => handleStatusChange('pending')}
                    disabled={donation.payment_status === 'pending' || statusUpdating}
                    className={`px-3 py-1 text-xs rounded ${
                      donation.payment_status === 'pending'
                        ? 'bg-gray-100 text-gray-500 cursor-not-allowed'
                        : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'
                    }`}
                  >
                    Beklemede
                  </button>
                  <button
                    onClick={() => handleStatusChange('failed')}
                    disabled={donation.payment_status === 'failed' || statusUpdating}
                    className={`px-3 py-1 text-xs rounded ${
                      donation.payment_status === 'failed'
                        ? 'bg-gray-100 text-gray-500 cursor-not-allowed'
                        : 'bg-red-100 text-red-800 hover:bg-red-200'
                    }`}
                  >
                    Başarısız
                  </button>
                </div>
              </div>
            </div>
            
            <div>
              <h2 className="text-lg font-semibold mb-4 border-b pb-2">Bağışçı Bilgileri</h2>
              
              <div className="space-y-3">
                <div className="flex justify-between">
                  <span className="text-gray-600">Ad Soyad:</span>
                  <span className="font-medium">{donation.donor_name}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Bağışçı Tipi:</span>
                  <span>{donation.donor_type === 'individual' ? 'Bireysel' : 'Kurumsal'}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">E-posta:</span>
                  <span>{donation.donor_email}</span>
                </div>
                {donation.donor_phone && (
                  <div className="flex justify-between">
                    <span className="text-gray-600">Telefon:</span>
                    <span>{donation.donor_phone}</span>
                  </div>
                )}
                {donation.city && (
                  <div className="flex justify-between">
                    <span className="text-gray-600">Şehir:</span>
                    <span>{donation.city}</span>
                  </div>
                )}
              </div>
            </div>
          </div>
          
          {/* Notlar */}
          {donation.notes && (
            <div className="mt-6 pt-4 border-t">
              <h2 className="text-lg font-semibold mb-2">Notlar</h2>
              <div className="bg-gray-50 p-4 rounded">
                <p className="text-gray-700 whitespace-pre-line">{donation.notes}</p>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
} 