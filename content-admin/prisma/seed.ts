import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

async function main() {
  try {
    const hashedPassword = await bcrypt.hash('admin123', 10);

    const user = await prisma.contentUser.upsert({
      where: { email: 'admin@example.com' },
      update: {},
      create: {
        email: 'admin@example.com',
        name: 'Admin',
        password: hashedPassword,
        role: 'admin',
      },
    });

    console.log('Seed başarılı:', { user });
  } catch (error) {
    console.error('Seed hatası:', error);
    throw error;
  }
}

main()
  .catch((e) => {
    console.error('Kritik hata:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  }); 