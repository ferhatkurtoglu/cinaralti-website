import { executeQuery } from '@/lib/db';
import { NextResponse } from 'next/server';

export async function GET(request: Request) {
  try {
    const { searchParams } = new URL(request.url);
    const period = searchParams.get('period') || 'monthly';
    
    let dateFormat = '';
    let groupBy = '';
    
    // Periyoda göre tarih formatını belirle
    switch (period) {
      case 'daily':
        dateFormat = '%Y-%m-%d';
        groupBy = 'DATE(created_at)';
        break;
      case 'weekly':
        dateFormat = '%Y-%u';
        groupBy = 'YEARWEEK(created_at)';
        break;
      case 'monthly':
        dateFormat = '%Y-%m';
        groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
        break;
      case 'yearly':
        dateFormat = '%Y';
        groupBy = 'YEAR(created_at)';
        break;
      default:
        dateFormat = '%Y-%m';
        groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
    }

    // Genel istatistikler
    const generalStatsQuery = `
      SELECT 
        COUNT(*) as total_donations,
        SUM(amount) as total_amount,
        AVG(amount) as avg_amount,
        COUNT(DISTINCT donor_email) as unique_donors,
        COUNT(CASE WHEN payment_status = 'completed' THEN 1 END) as completed_donations,
        SUM(CASE WHEN payment_status = 'completed' THEN amount ELSE 0 END) as completed_amount
      FROM donations_made
    `;
    
    const generalStats = await executeQuery({ query: generalStatsQuery });
    
    // Trend verileri
    const trendQuery = `
      SELECT 
        DATE_FORMAT(created_at, ?) as period,
        SUM(amount) as total_amount,
        COUNT(*) as donation_count
      FROM donations_made 
      WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)
      GROUP BY ${groupBy}
      ORDER BY period DESC
      LIMIT 12
    `;
    
    const trendData = await executeQuery({ 
      query: trendQuery, 
      values: [dateFormat] 
    });
    
    // Kategori bazlı istatistikler
    const categoryQuery = `
      SELECT 
        donation_category,
        COUNT(*) as donation_count,
        SUM(amount) as total_amount,
        AVG(amount) as avg_amount
      FROM donations_made 
      WHERE donation_category IS NOT NULL
      GROUP BY donation_category
      ORDER BY total_amount DESC
    `;
    
    const categoryData = await executeQuery({ query: categoryQuery });
    
    // Şehir bazlı istatistikler
    const cityQuery = `
      SELECT 
        city,
        COUNT(*) as donation_count,
        SUM(amount) as total_amount,
        AVG(amount) as avg_amount
      FROM donations_made 
      WHERE city IS NOT NULL AND city != ''
      GROUP BY city
      ORDER BY total_amount DESC
      LIMIT 10
    `;
    
    const cityData = await executeQuery({ query: cityQuery });
    
    // Bağış türü bazlı istatistikler
    const donationTypeQuery = `
      SELECT 
        donation_option,
        COUNT(*) as donation_count,
        SUM(amount) as total_amount,
        AVG(amount) as avg_amount
      FROM donations_made 
      WHERE donation_option IS NOT NULL
      GROUP BY donation_option
      ORDER BY total_amount DESC
    `;
    
    const donationTypeData = await executeQuery({ query: donationTypeQuery });
    
    // Ödeme durumu bazlı istatistikler
    const paymentStatusQuery = `
      SELECT 
        payment_status,
        COUNT(*) as donation_count,
        SUM(amount) as total_amount
      FROM donations_made 
      GROUP BY payment_status
      ORDER BY total_amount DESC
    `;
    
    const paymentStatusData = await executeQuery({ query: paymentStatusQuery });
    
    // Son 7 günlük bağışlar
    const recentDonationsQuery = `
      SELECT 
        id,
        donor_name,
        donor_email,
        amount,
        donation_category,
        payment_status,
        created_at
      FROM donations_made 
      WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
      ORDER BY created_at DESC
      LIMIT 10
    `;
    
    const recentDonations = await executeQuery({ query: recentDonationsQuery });
    
    // Aylık bağış trendi (son 12 ay)
    const monthlyTrendQuery = `
      SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        SUM(amount) as total_amount,
        COUNT(*) as donation_count
      FROM donations_made 
      WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
      GROUP BY DATE_FORMAT(created_at, '%Y-%m')
      ORDER BY month DESC
    `;
    
    const monthlyTrend = await executeQuery({ query: monthlyTrendQuery });
    
    return NextResponse.json({
      general: generalStats[0],
      trend: trendData,
      categories: categoryData,
      cities: cityData,
      donationTypes: donationTypeData,
      paymentStatus: paymentStatusData,
      recentDonations,
      monthlyTrend
    });
    
  } catch (error) {
    console.error('İstatistikler getirilirken hata oluştu:', error);
    return NextResponse.json(
      { error: 'İstatistikler getirilirken bir hata oluştu.' },
      { status: 500 }
    );
  }
} 