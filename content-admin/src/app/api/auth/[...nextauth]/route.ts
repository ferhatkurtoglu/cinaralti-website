import { PrismaClient } from '@prisma/client';
import { compare } from 'bcryptjs';
import NextAuth, { type NextAuthOptions } from 'next-auth';
import CredentialsProvider from 'next-auth/providers/credentials';

const prisma = new PrismaClient();

export const authOptions: NextAuthOptions = {
  pages: {
    signIn: '/login',
  },
  session: {
    strategy: 'jwt',
  },
  debug: true,
  providers: [
    CredentialsProvider({
      name: 'Sign in',
      credentials: {
        email: {
          label: 'Email',
          type: 'email',
          placeholder: 'example@example.com',
        },
        password: { label: 'Password', type: 'password' },
      },
      async authorize(credentials) {
        console.log('🔍 Authorize called with:', credentials);
        
        if (!credentials?.email || !credentials.password) {
          console.log('❌ Missing credentials');
          return null;
        }

        console.log('🔍 Searching for user:', credentials.email);
        const user = await prisma.contentAdminUser.findUnique({
          where: {
            email: credentials.email,
          },
        });

        if (!user) {
          console.log('❌ User not found');
          return null;
        }

        console.log('✅ User found:', user.email);
        const isPasswordValid = await compare(
          credentials.password,
          user.password
        );

        console.log('🔍 Password validation:', isPasswordValid);
        if (!isPasswordValid) {
          console.log('❌ Invalid password');
          return null;
        }

        return {
          id: user.id.toString(),
          email: user.email,
          name: user.name,
          role: user.role,
        };
      },
    }),
  ],
  callbacks: {
    session: ({ session, token }) => {
      return {
        ...session,
        user: {
          ...session.user,
          id: parseInt(token.id as string),
          role: token.role,
        },
      };
    },
    jwt: ({ token, user }) => {
      if (user) {
        return {
          ...token,
          id: user.id,
          role: user.role,
        };
      }
      return token;
    },
  },
};

const handler = NextAuth(authOptions);
export { handler as GET, handler as POST };
