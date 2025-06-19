'use client';

import DashboardLayout from '@/components/layout/DashboardLayout';
import {
    DocumentTextIcon,
    TagIcon,
    VideoCameraIcon,
} from '@heroicons/react/24/outline';
import { useSession } from 'next-auth/react';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';

const stats = [
  {
    name: 'Blog Yazıları',
    value: '0',
    icon: DocumentTextIcon,
    href: '/dashboard/blog',
  },
  {
    name: 'Videolar',
    value: '0',
    icon: VideoCameraIcon,
    href: '/dashboard/videos',
  },
  {
    name: 'Kategoriler',
    value: '0',
    icon: TagIcon,
    href: '/dashboard/categories',
  },
];

export default function DashboardPage() {
  const { data: session, status } = useSession();
  const router = useRouter();

  useEffect(() => {
    if (status === 'unauthenticated') {
      router.push('/login');
    }
  }, [status, router]);

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
          <h1 className="text-2xl font-semibold text-gray-900">Dashboard</h1>
        </div>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
          <div className="py-4">
            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
              {stats.map((item) => (
                <a
                  key={item.name}
                  href={item.href}
                  className="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden hover:bg-gray-50"
                >
                  <dt>
                    <div className="absolute bg-indigo-500 rounded-md p-3">
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
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
} 