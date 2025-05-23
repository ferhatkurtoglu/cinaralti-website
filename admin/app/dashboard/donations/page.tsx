'use client';

import DonationsList from '@/components/DonationsList';

export default function DonationsPage() {
  return (
    <div className="container-fluid px-4">
      <div className="row">
        <div className="col-12">
          <h1 className="mt-4 mb-4">Bağışlar Yönetimi</h1>
        </div>
      </div>
      
      <div className="row">
        <div className="col-12">
          <DonationsList />
        </div>
      </div>
    </div>
  );
} 