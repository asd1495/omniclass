import React, { createContext, useState, useEffect, useMemo } from 'react';
import api from '../services/api';

interface User {
  id: number;
  name: string;
  email: string;
}

interface AuthContextType {
  user: User | null;
  token: string | null;
  login: (token: string) => Promise<void>;
  logout: () => void;
  isAuthenticated: boolean;
  isLoading: boolean;
}

export const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(localStorage.getItem('token'));
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    let isMounted = true;
    
    if (token) {
      api.get('/user')
        .then(response => {
          if (isMounted) {
            setUser(response.data);
            setIsLoading(false);
          }
        })
        .catch(() => {
          if (isMounted) {
            setUser(null);
            setToken(null);
            localStorage.removeItem('token');
            setIsLoading(false);
          }
        });
    } else {
      setIsLoading(false);
    }

    return () => { isMounted = false; };
  }, [token]);

  const login = async (newToken: string) => {
    localStorage.setItem('token', newToken);
    setToken(newToken);
  };

  const logout = () => {
    api.post('/logout').finally(() => {
      localStorage.removeItem('token');
      setToken(null);
      setUser(null);
    });
  };

  const value = useMemo(() => ({
    user,
    token,
    login,
    logout,
    isAuthenticated: !!token,
    isLoading
  }), [user, token, isLoading]);

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};
