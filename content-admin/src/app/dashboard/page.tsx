'use client';

import DashboardLayout from '@/components/layout/DashboardLayout';
import {
  DocumentTextIcon,
  TagIcon,
  VideoCameraIcon,
} from '@heroicons/react/24/outline';
import { useSession } from 'next-auth/react';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';

type Stats = {
  blogCount: number;
  videoCount: number;
  categoryCount: number;
};

export default function DashboardPage() {
  const { data: session, status } = useSession();
  const router = useRouter();
  const [stats, setStats] = useState<Stats>({
    blogCount: 0,
    videoCount: 0,
    categoryCount: 0,
  });
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (status === 'unauthenticated') {
      router.push('/login');
    }
  }, [status, router]);

  useEffect(() => {
    const fetchStats = async () => {
      if (status !== 'authenticated') return;

      try {
        setIsLoading(true);
        
        // Paralel API çağrıları - kategorileri ayrı ayrı say
        const [blogsResponse, videosResponse, blogCategoriesResponse, videoCategoriesResponse] = await Promise.all([
          fetch('/api/blog'),
          fetch('/api/videos'),
          fetch('/api/categories?type=blog'),
          fetch('/api/categories?type=video')
        ]);

        const [blogs, videos, blogCategories, videoCategories] = await Promise.all([
          blogsResponse.json(),
          videosResponse.json(),
          blogCategoriesResponse.json(),
          videoCategoriesResponse.json()
        ]);

        setStats({
          blogCount: Array.isArray(blogs) ? blogs.length : 0,
          videoCount: Array.isArray(videos) ? videos.length : 0,
          categoryCount: (Array.isArray(blogCategories) ? blogCategories.length : 0) + 
                        (Array.isArray(videoCategories) ? videoCategories.length : 0),
        });
      } catch (error) {
        console.error('İstatistikler yüklenirken hata oluştu:', error);
        // Hata durumunda 0 değerlerini koru
      } finally {
        setIsLoading(false);
      }
    };

    fetchStats();
  }, [status]);

  const statsItems = [
    {
      name: 'Blog Yazıları',
      value: isLoading ? '-' : stats.blogCount.toString(),
      icon: DocumentTextIcon,
      href: '/dashboard/blog',
      color: 'bg-blue-500',
    },
    {
      name: 'Videolar',
      value: isLoading ? '-' : stats.videoCount.toString(),
      icon: VideoCameraIcon,
      href: '/dashboard/videos',
      color: 'bg-purple-500',
    },
    {
      name: 'Kategoriler',
      value: isLoading ? '-' : stats.categoryCount.toString(),
      icon: TagIcon,
      href: '/dashboard/categories',
      color: 'bg-green-500',
    },
  ];

  if (status === 'loading') {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
          <p className="mt-4 text-gray-600">Yükleniyor...</p>
        </div>
      </div>
    );
  }

  return (
    <DashboardLayout>
      <div className="py-6">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="md:flex md:items-center md:justify-between">
            <div className="min-w-0 flex-1">
              <h1 className="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Dashboard
              </h1>
              <p className="mt-1 text-sm text-gray-500">
                İçerik yönetim sistemi genel bakış
              </p>
            </div>
          </div>
        </div>
        
        <div className="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
          <div className="py-6">
            {/* İstatistik Kartları */}
            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
              {statsItems.map((item) => (
                <a
                  key={item.name}
                  href={item.href}
                  className="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden hover:bg-gray-50 transition-colors duration-200"
                >
                  <dt>
                    <div className={`absolute ${item.color} rounded-md p-3`}>
                      <item.icon
                        className="h-6 w-6 text-white"
                        aria-hidden="true"
                      />
                    </div>
                    <p className="ml-16 text-sm font-medium text-gray-500 truncate">
                      {item.name}
                    </p>
                  </dt>
                  <dd className="ml-16 pb-6 flex items-baseline sm:pb-7">
                    <p className="text-2xl font-semibold text-gray-900">
                      {item.value}
                    </p>
                    <div className="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
                      <div className="text-sm">
                        <span className="font-medium text-indigo-600 hover:text-indigo-500">
                          Tümünü görüntüle
                          <span className="sr-only"> {item.name}</span>
                        </span>
                      </div>
                    </div>
                  </dd>
                </a>
              ))}
            </div>

            {/* Hızlı Eylemler */}
            <div className="bg-white shadow rounded-lg">
              <div className="px-4 py-5 sm:p-6">
                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                  Hızlı Eylemler
                </h3>
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                  <a
                    href="/dashboard/blog/new"
                    className="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                  >
                    <DocumentTextIcon className="h-5 w-5 mr-2 text-gray-400" />
                    Yeni Blog Yazısı
                  </a>
                  <a
                    href="/dashboard/videos/new"
                    className="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                  >
                    <VideoCameraIcon className="h-5 w-5 mr-2 text-gray-400" />
                    Yeni Video
                  </a>
                  <a
                    href="/dashboard/categories"
                    className="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                  >
                    <TagIcon className="h-5 w-5 mr-2 text-gray-400" />
                    Kategorileri Yönet
                  </a>
                </div>
              </div>
            </div>

            {/* Sistem Durumu */}
            <div className="mt-8 bg-white shadow rounded-lg">
              <div className="px-4 py-5 sm:p-6">
                <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
                  Sistem Durumu
                </h3>
                <div className="space-y-3">
                  <div className="flex items-center justify-between">
                    <span className="text-sm text-gray-600">API Bağlantısı</span>
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      ● Aktif
                    </span>
                  </div>
                  <div className="flex items-center justify-between">
                    <span className="text-sm text-gray-600">Veritabanı</span>
                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      ● Bağlı
                    </span>
                  </div>
                  <div className="flex items-center justify-between">
                    <span className="text-sm text-gray-600">Son Güncelleme</span>
                    <span className="text-sm text-gray-900">
                      {new Date().toLocaleDateString('tr-TR')} {new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' })}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
} 