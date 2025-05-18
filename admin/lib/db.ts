import mysql from 'mysql2/promise';

const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASS || '',
  database: process.env.DB_NAME || 'cinaralti_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

export async function executeQuery({ query, values = [] }: { query: string; values?: any[] }): Promise<any> {
  try {
    const [results] = await pool.execute(query, values);
    return results;
  } catch (error) {
    console.error('Database query error:', error);
    throw error;
  }
}

// Kullanıcılar için CRUD işlemleri
export async function getUsers() {
  const query = `
    SELECT * FROM users
    ORDER BY created_at DESC
  `;
  return executeQuery({ query });
}

export async function getUserById(id: number) {
  const query = `
    SELECT * FROM users
    WHERE id = ?
  `;
  const results = await executeQuery({ query, values: [id] });
  return results[0];
}

export async function createUser(userData: any) {
  const query = `
    INSERT INTO users (name, email, password, role, status)
    VALUES (?, ?, ?, ?, ?)
  `;
  
  return executeQuery({
    query,
    values: [
      userData.name,
      userData.email,
      userData.password, // Gerçek uygulamada şifre hashlenmelidir
      userData.role || 'viewer',
      userData.status || 'active'
    ]
  });
}

export async function updateUser(id: number, userData: any) {
  let query = `UPDATE users SET `;
  const values = [];
  const updateFields = [];
  
  if (userData.name) {
    updateFields.push('name = ?');
    values.push(userData.name);
  }
  
  if (userData.email) {
    updateFields.push('email = ?');
    values.push(userData.email);
  }
  
  if (userData.password) {
    updateFields.push('password = ?');
    values.push(userData.password); // Gerçek uygulamada şifre hashlenmelidir
  }
  
  if (userData.role) {
    updateFields.push('role = ?');
    values.push(userData.role);
  }
  
  if (userData.status) {
    updateFields.push('status = ?');
    values.push(userData.status);
  }
  
  if (updateFields.length === 0) {
    throw new Error('No fields to update');
  }
  
  query += updateFields.join(', ');
  query += ' WHERE id = ?';
  values.push(id);
  
  return executeQuery({ query, values });
}

export async function deleteUser(id: number) {
  const query = `
    DELETE FROM users
    WHERE id = ?
  `;
  return executeQuery({ query, values: [id] });
}

// Bağış Kategorileri için CRUD işlemleri
export async function getCategories() {
  const query = `
    SELECT * FROM donation_categories
    ORDER BY name
  `;
  return executeQuery({ query });
}

export async function getCategoryById(id: number) {
  const query = `
    SELECT * FROM donation_categories
    WHERE id = ?
  `;
  const results = await executeQuery({ query, values: [id] });
  return results[0];
}

export async function createCategory(categoryData: any) {
  const query = `
    INSERT INTO donation_categories (name, slug)
    VALUES (?, ?)
  `;
  
  return executeQuery({
    query,
    values: [
      categoryData.name,
      categoryData.slug || categoryData.name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')
    ]
  });
}

export async function updateCategory(id: number, categoryData: any) {
  const query = `
    UPDATE donation_categories
    SET name = ?, slug = ?
    WHERE id = ?
  `;
  
  return executeQuery({
    query,
    values: [
      categoryData.name,
      categoryData.slug || categoryData.name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''),
      id
    ]
  });
}

export async function deleteCategory(id: number) {
  const query = `
    DELETE FROM donation_categories
    WHERE id = ?
  `;
  return executeQuery({ query, values: [id] });
}

// Bağış Türleri için CRUD işlemleri
export async function getDonationTypes() {
  const query = `
    SELECT * FROM donation_options
    ORDER BY title
  `;
  return executeQuery({ query });
}

export async function getDonationTypeById(id: number) {
  const query = `
    SELECT dt.*
    FROM donation_options dt
    WHERE dt.id = ?
  `;
  const donationType = await executeQuery({ query, values: [id] });
  
  if (donationType.length === 0) {
    return null;
  }
  
  // Kategorileri al
  const categoriesQuery = `
    SELECT dc.*
    FROM donation_categories dc
    JOIN donation_option_categories dtc ON dc.id = dtc.category_id
    WHERE dtc.donation_option_id = ?
  `;
  
  const categories = await executeQuery({ query: categoriesQuery, values: [id] });
  
  return { ...donationType[0], categories };
}

export async function createDonationType(typeData: any) {
  const connection = await pool.getConnection();
  
  try {
    await connection.beginTransaction();
    
    // Ana bağış türü bilgilerini ekle
    const insertQuery = `
      INSERT INTO donation_options (
        title, 
        slug, 
        description, 
        active, 
        category_id, 
        target_amount, 
        collected_amount, 
        position,
        cover_image,
        gallery_images
      )
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `;
    
    const [result] = await connection.execute(insertQuery, [
      typeData.title || typeData.name,
      typeData.slug || (typeData.title || typeData.name).toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''),
      typeData.description || '',
      typeData.active !== undefined ? typeData.active : true,
      typeData.category_id || null,
      typeData.target_amount || 0,
      typeData.collected_amount || 0,
      typeData.position || 0,
      typeData.cover_image || null,
      Array.isArray(typeData.gallery_images) ? JSON.stringify(typeData.gallery_images) : null
    ]);
    
    const typeId = (result as any).insertId;
    
    // Kategorileri ekle (eğer donation_option_categories tablosu hala kullanılıyorsa)
    if (typeData.categories && typeData.categories.length > 0) {
      const categoryQuery = `
        INSERT INTO donation_option_categories (donation_option_id, category_id)
        VALUES (?, ?)
      `;
      
      for (const categoryId of typeData.categories) {
        await connection.execute(categoryQuery, [typeId, categoryId]);
      }
    }
    
    await connection.commit();
    return { id: typeId, ...typeData };
  } catch (error) {
    await connection.rollback();
    throw error;
  } finally {
    connection.release();
  }
}

export async function updateDonationType(id: number, typeData: any) {
  const connection = await pool.getConnection();
  
  try {
    await connection.beginTransaction();
    
    // Ana bağış türü bilgilerini güncelle
    const updateQuery = `
      UPDATE donation_options
      SET 
        title = ?, 
        slug = ?, 
        description = ?, 
        active = ?,
        category_id = ?,
        target_amount = ?,
        collected_amount = ?,
        position = ?,
        cover_image = ?,
        gallery_images = ?
      WHERE id = ?
    `;
    
    await connection.execute(updateQuery, [
      typeData.title || typeData.name,
      typeData.slug || (typeData.title || typeData.name).toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''),
      typeData.description || '',
      typeData.active !== undefined ? typeData.active : true,
      typeData.category_id || null,
      typeData.target_amount || 0,
      typeData.collected_amount || 0,
      typeData.position || 0,
      typeData.cover_image || null,
      Array.isArray(typeData.gallery_images) ? JSON.stringify(typeData.gallery_images) : null,
      id
    ]);
    
    // Eğer donation_option_categories tablosu hala kullanılıyorsa
    if (typeData.categories !== undefined) {
      // Eski kategorileri temizle
      await connection.execute(
        'DELETE FROM donation_option_categories WHERE donation_option_id = ?',
        [id]
      );
      
      // Yeni kategorileri ekle
      if (typeData.categories && typeData.categories.length > 0) {
        const categoryQuery = `
          INSERT INTO donation_option_categories (donation_option_id, category_id)
          VALUES (?, ?)
        `;
        
        for (const categoryId of typeData.categories) {
          await connection.execute(categoryQuery, [id, categoryId]);
        }
      }
    }
    
    await connection.commit();
    return { id, ...typeData };
  } catch (error) {
    await connection.rollback();
    throw error;
  } finally {
    connection.release();
  }
}

export async function deleteDonationType(id: number) {
  const connection = await pool.getConnection();
  
  try {
    await connection.beginTransaction();
    
    // Önce kategorileri temizle
    await connection.execute(
      'DELETE FROM donation_option_categories WHERE donation_option_id = ?',
      [id]
    );
    
    // Sonra ana kaydı sil
    await connection.execute(
      'DELETE FROM donation_options WHERE id = ?',
      [id]
    );
    
    await connection.commit();
    return true;
  } catch (error) {
    await connection.rollback();
    throw error;
  } finally {
    connection.release();
  }
}

// Bağışlar için CRUD işlemleri
export async function getDonations() {
  const query = `
    SELECT d.*, dt.name as donation_type_name
    FROM donations d
    JOIN donation_options dt ON d.donation_option_id = dt.id
    ORDER BY d.donation_date DESC
  `;
  return executeQuery({ query });
}

export async function getDonationById(id: number) {
  const query = `
    SELECT d.*, dt.name as donation_type_name
    FROM donations d
    JOIN donation_options dt ON d.donation_option_id = dt.id
    WHERE d.id = ?
  `;
  const results = await executeQuery({ query, values: [id] });
  return results[0];
}

export async function createDonation(donationData: any) {
  const query = `
    INSERT INTO donations (
      donation_option_id, amount, donor_name, donor_email, donor_phone,
      payment_method, payment_status, donation_date, note
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
  `;
  
  return executeQuery({
    query,
    values: [
      donationData.donation_option_id,
      donationData.amount,
      donationData.donor_name || null,
      donationData.donor_email || null,
      donationData.donor_phone || null,
      donationData.payment_method || 'Banka',
      donationData.payment_status || 'Beklemede',
      donationData.donation_date || new Date(),
      donationData.note || null
    ]
  });
}

export async function updateDonation(id: number, donationData: any) {
  let query = `UPDATE donations SET `;
  const values = [];
  const updateFields = [];
  
  if (donationData.donation_option_id) {
    updateFields.push('donation_option_id = ?');
    values.push(donationData.donation_option_id);
  }
  
  if (donationData.amount) {
    updateFields.push('amount = ?');
    values.push(donationData.amount);
  }
  
  if (donationData.donor_name !== undefined) {
    updateFields.push('donor_name = ?');
    values.push(donationData.donor_name);
  }
  
  if (donationData.donor_email !== undefined) {
    updateFields.push('donor_email = ?');
    values.push(donationData.donor_email);
  }
  
  if (donationData.donor_phone !== undefined) {
    updateFields.push('donor_phone = ?');
    values.push(donationData.donor_phone);
  }
  
  if (donationData.payment_method) {
    updateFields.push('payment_method = ?');
    values.push(donationData.payment_method);
  }
  
  if (donationData.payment_status) {
    updateFields.push('payment_status = ?');
    values.push(donationData.payment_status);
  }
  
  if (donationData.donation_date) {
    updateFields.push('donation_date = ?');
    values.push(donationData.donation_date);
  }
  
  if (donationData.note !== undefined) {
    updateFields.push('note = ?');
    values.push(donationData.note);
  }
  
  if (updateFields.length === 0) {
    throw new Error('No fields to update');
  }
  
  query += updateFields.join(', ');
  query += ' WHERE id = ?';
  values.push(id);
  
  return executeQuery({ query, values });
}

export async function deleteDonation(id: number) {
  const query = `
    DELETE FROM donations
    WHERE id = ?
  `;
  return executeQuery({ query, values: [id] });
}

// Bağış özetlerini dönem bazında çeken fonksiyon
export async function getDonationSummary(period: string) {
  const currentDate = new Date();
  let startDate;
  let groupBy;
  let format;

  switch (period) {
    case 'daily':
      // Son 7 gün
      startDate = new Date(currentDate);
      startDate.setDate(startDate.getDate() - 7);
      groupBy = 'DATE(donation_date)';
      format = '%Y-%m-%d';
      break;
    case 'weekly':
      // Son 8 hafta
      startDate = new Date(currentDate);
      startDate.setDate(startDate.getDate() - 56);
      groupBy = 'YEARWEEK(donation_date)';
      format = '%Y-%u';
      break;
    case 'monthly':
      // Son 12 ay
      startDate = new Date(currentDate);
      startDate.setMonth(startDate.getMonth() - 12);
      groupBy = 'CONCAT(YEAR(donation_date), "-", MONTH(donation_date))';
      format = '%Y-%m';
      break;
    case 'yearly':
      // Son 5 yıl
      startDate = new Date(currentDate);
      startDate.setFullYear(startDate.getFullYear() - 5);
      groupBy = 'YEAR(donation_date)';
      format = '%Y';
      break;
    default:
      // Varsayılan olarak aylık
      startDate = new Date(currentDate);
      startDate.setMonth(startDate.getMonth() - 12);
      groupBy = 'CONCAT(YEAR(donation_date), "-", MONTH(donation_date))';
      format = '%Y-%m';
  }

  const query = `
    SELECT 
      DATE_FORMAT(donation_date, ?) as period,
      SUM(amount) as total_amount,
      COUNT(*) as donation_count
    FROM 
      donations
    WHERE 
      donation_date >= ? AND
      payment_status = 'Tamamlandı'
    GROUP BY 
      ${groupBy}
    ORDER BY 
      MIN(donation_date)
  `;

  return executeQuery({
    query,
    values: [format, startDate]
  });
}

// Bağış kategorilerine göre istatistik çeken fonksiyon
export async function getDonationsByCategory(period: string) {
  const currentDate = new Date();
  let startDate;

  switch (period) {
    case 'daily':
      startDate = new Date(currentDate);
      startDate.setDate(startDate.getDate() - 1);
      break;
    case 'weekly':
      startDate = new Date(currentDate);
      startDate.setDate(startDate.getDate() - 7);
      break;
    case 'monthly':
      startDate = new Date(currentDate);
      startDate.setMonth(startDate.getMonth() - 1);
      break;
    case 'yearly':
      startDate = new Date(currentDate);
      startDate.setFullYear(startDate.getFullYear() - 1);
      break;
    default:
      startDate = new Date(currentDate);
      startDate.setMonth(startDate.getMonth() - 1);
  }

  const query = `
    SELECT 
      c.name as category_name,
      SUM(d.amount) as total_amount,
      COUNT(d.id) as donation_count
    FROM 
      donations d
    JOIN 
      donation_options dt ON d.donation_option_id = dt.id
    JOIN 
      donation_option_categories dtc ON dt.id = dtc.donation_option_id
    JOIN 
      donation_categories c ON dtc.category_id = c.id
    WHERE 
      d.donation_date >= ? AND
      d.payment_status = 'Tamamlandı'
    GROUP BY 
      c.id
    ORDER BY 
      total_amount DESC
  `;

  return executeQuery({
    query,
    values: [startDate]
  });
}

// Son bağışları çeken fonksiyon
export async function getRecentDonations(limit = 10) {
  const query = `
    SELECT 
      d.id,
      d.amount,
      d.donor_name,
      d.donation_date,
      d.payment_status,
      dt.name as donation_type
    FROM 
      donations d
    JOIN 
      donation_options dt ON d.donation_option_id = dt.id
    ORDER BY 
      d.donation_date DESC
    LIMIT ?
  `;

  return executeQuery({
    query,
    values: [limit]
  });
} 