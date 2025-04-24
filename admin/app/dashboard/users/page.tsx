'use client';

import axios from 'axios';
import { useEffect, useState } from 'react';
import { FaEdit, FaEye, FaSearch, FaTrash, FaUserCog, FaUserPlus, FaUserShield } from 'react-icons/fa';
import Swal from 'sweetalert2';

interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'editor' | 'viewer';
  avatar?: string;
  last_login?: string;
  status: 'active' | 'inactive';
  created_at: string;
}

export default function Users() {
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [search, setSearch] = useState<string>('');
  const [showModal, setShowModal] = useState<boolean>(false);
  const [modalMode, setModalMode] = useState<'add' | 'edit'>('add');
  const [currentUser, setCurrentUser] = useState<User | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    loadUsers();
  }, []);

  const loadUsers = async () => {
    setLoading(true);
    try {
      const response = await axios.get('/api/users');
      setUsers(response.data);
      setError(null);
    } catch (err) {
      console.error('Kullanıcılar yüklenirken hata oluştu:', err);
      setError('Kullanıcılar yüklenirken bir hata oluştu');
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearch(e.target.value);
  };

  const filteredUsers = users.filter(user =>
    user.name.toLowerCase().includes(search.toLowerCase()) ||
    user.email.toLowerCase().includes(search.toLowerCase()) ||
    user.role.toLowerCase().includes(search.toLowerCase())
  );

  const handleAdd = () => {
    setCurrentUser(null);
    setModalMode('add');
    setShowModal(true);
  };

  const handleEdit = (user: User) => {
    setCurrentUser(user);
    setModalMode('edit');
    setShowModal(true);
  };

  const handleDelete = async (id: number) => {
    const result = await Swal.fire({
      title: 'Emin misiniz?',
      text: 'Bu kullanıcıyı silmek istediğinizden emin misiniz?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet, sil!',
      cancelButtonText: 'İptal'
    });

    if (result.isConfirmed) {
      try {
        await axios.delete(`/api/users/${id}`);
        await Swal.fire('Silindi!', 'Kullanıcı başarıyla silindi.', 'success');
        loadUsers();
      } catch (error) {
        console.error('Kullanıcı silinirken hata oluştu:', error);
        Swal.fire('Hata', 'Kullanıcı silinirken bir hata oluştu.', 'error');
      }
    }
  };

  const handleSave = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    
    const form = e.currentTarget;
    const name = (form.elements.namedItem('name') as HTMLInputElement).value;
    const email = (form.elements.namedItem('email') as HTMLInputElement).value;
    const role = (form.elements.namedItem('role') as HTMLSelectElement).value as 'admin' | 'editor' | 'viewer';
    const status = (form.elements.namedItem('status') as HTMLSelectElement).value as 'active' | 'inactive';
    
    const userData = {
      name,
      email,
      role,
      status
    };
    
    try {
      if (modalMode === 'add') {
        const password = (form.elements.namedItem('password') as HTMLInputElement).value;
        await axios.post('/api/users', { ...userData, password });
        Swal.fire('Başarılı', 'Yeni kullanıcı eklendi', 'success');
      } else if (modalMode === 'edit' && currentUser) {
        const password = (form.elements.namedItem('password') as HTMLInputElement).value;
        const updateData = password ? { ...userData, password } : userData;
        await axios.put(`/api/users/${currentUser.id}`, updateData);
        Swal.fire('Başarılı', 'Kullanıcı güncellendi', 'success');
      }
      
      setShowModal(false);
      loadUsers();
    } catch (error) {
      console.error('Kullanıcı kaydedilirken hata oluştu:', error);
      Swal.fire('Hata', 'Kullanıcı kaydedilirken bir hata oluştu', 'error');
    }
  };

  return (
    <div>
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 className="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Kullanıcılar</h1>
        
        <button 
          className="btn btn-primary flex items-center"
          onClick={handleAdd}
        >
          <FaUserPlus className="mr-2" /> Kullanıcı Ekle
        </button>
      </div>

      <div className="card mb-6">
        <div className="p-4 border-b border-gray-200">
          <div className="flex items-center">
            <div className="relative flex-1">
              <input
                type="text"
                className="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="İsim, e-posta veya rol ara..."
                value={search}
                onChange={handleSearch}
              />
              <FaSearch className="absolute left-3 top-3 text-gray-400" />
            </div>
          </div>
        </div>
        
        {loading ? (
          <div className="p-8 text-center">
            <div className="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-primary border-r-transparent"></div>
            <p className="mt-2 text-gray-600">Yükleniyor...</p>
          </div>
        ) : error ? (
          <div className="p-8 text-center">
            <p className="text-red-500">{error}</p>
            <button 
              className="mt-4 btn btn-primary"
              onClick={loadUsers}
            >
              Yeniden Dene
            </button>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Son Giriş</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oluşturulma</th>
                  <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {filteredUsers.length > 0 ? (
                  filteredUsers.map(user => (
                    <tr key={user.id}>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center">
                          <div className="flex-shrink-0 h-10 w-10">
                            {user.avatar ? (
                              <img 
                                className="h-10 w-10 rounded-full" 
                                src={user.avatar} 
                                alt={user.name} 
                              />
                            ) : (
                              <div className="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white">
                                {user.name.charAt(0).toUpperCase()}
                              </div>
                            )}
                          </div>
                          <div className="ml-4">
                            <div className="text-sm font-medium text-gray-900">{user.name}</div>
                            <div className="text-sm text-gray-500">{user.email}</div>
                          </div>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                          user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                          user.role === 'editor' ? 'bg-blue-100 text-blue-800' : 
                          'bg-gray-100 text-gray-800'
                        }`}>
                          {user.role === 'admin' && <FaUserShield className="mr-1" />}
                          {user.role === 'editor' && <FaUserCog className="mr-1" />}
                          {user.role === 'viewer' && <FaEye className="mr-1" />}
                          {user.role === 'admin' ? 'Yönetici' : 
                           user.role === 'editor' ? 'Editör' : 
                           'İzleyici'}
                        </span>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                          user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        }`}>
                          {user.status === 'active' ? 'Aktif' : 'Pasif'}
                        </span>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">
                          {user.last_login ? new Date(user.last_login).toLocaleDateString('tr-TR') : '-'}
                        </div>
                        <div className="text-xs text-gray-500">
                          {user.last_login ? new Date(user.last_login).toLocaleTimeString('tr-TR') : ''}
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">
                          {new Date(user.created_at).toLocaleDateString('tr-TR')}
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button
                          onClick={() => handleEdit(user)}
                          className="text-primary hover:text-primary-dark mx-2"
                          title="Kullanıcıyı düzenle"
                          aria-label="Kullanıcıyı düzenle"
                        >
                          <FaEdit />
                        </button>
                        {user.role !== 'admin' && (
                          <button
                            onClick={() => handleDelete(user.id)}
                            className="text-red-500 hover:text-red-700 mx-2"
                            title="Kullanıcıyı sil"
                            aria-label="Kullanıcıyı sil"
                          >
                            <FaTrash />
                          </button>
                        )}
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={6} className="px-6 py-4 text-center text-gray-500">
                      {search ? 'Arama kriterlerine uygun kullanıcı bulunamadı.' : 'Henüz kullanıcı bulunmuyor.'}
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Kullanıcı Ekleme/Düzenleme Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 md:mx-auto">
            <div className="flex justify-between items-center p-6 border-b">
              <h3 className="text-lg font-medium text-gray-900">
                {modalMode === 'add' ? 'Yeni Kullanıcı Ekle' : 'Kullanıcıyı Düzenle'}
              </h3>
              <button
                type="button"
                className="text-gray-400 hover:text-gray-500"
                onClick={() => setShowModal(false)}
              >
                <span className="sr-only">Kapat</span>
                <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <form onSubmit={handleSave}>
              <div className="p-6 space-y-4">
                <div>
                  <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                    Ad Soyad
                  </label>
                  <input
                    type="text"
                    id="name"
                    name="name"
                    className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    required
                    defaultValue={currentUser?.name || ''}
                  />
                </div>
                
                <div>
                  <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                    E-posta
                  </label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    required
                    defaultValue={currentUser?.email || ''}
                  />
                </div>
                
                <div>
                  <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                    Şifre {modalMode === 'edit' && <span className="text-xs text-gray-500">(Boş bırakılırsa değişmez)</span>}
                  </label>
                  <input
                    type="password"
                    id="password"
                    name="password"
                    className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    required={modalMode === 'add'}
                  />
                </div>
                
                <div>
                  <label htmlFor="role" className="block text-sm font-medium text-gray-700">
                    Rol
                  </label>
                  <select
                    id="role"
                    name="role"
                    className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    defaultValue={currentUser?.role || 'viewer'}
                  >
                    <option value="admin">Yönetici</option>
                    <option value="editor">Editör</option>
                    <option value="viewer">İzleyici</option>
                  </select>
                </div>
                
                <div>
                  <label htmlFor="status" className="block text-sm font-medium text-gray-700">
                    Durum
                  </label>
                  <select
                    id="status"
                    name="status"
                    className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    defaultValue={currentUser?.status || 'active'}
                  >
                    <option value="active">Aktif</option>
                    <option value="inactive">Pasif</option>
                  </select>
                </div>
              </div>
              
              <div className="px-6 py-4 bg-gray-50 text-right rounded-b-lg">
                <button
                  type="button"
                  className="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 mr-3"
                  onClick={() => setShowModal(false)}
                >
                  İptal
                </button>
                <button
                  type="submit"
                  className="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark"
                >
                  Kaydet
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
} 