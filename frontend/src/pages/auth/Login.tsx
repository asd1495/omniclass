import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AlertCircle } from 'lucide-react';
import axios from 'axios';
import AuthLayout from './AuthLayout';
import { useAuth } from '../../hooks/useAuth';
import api from '../../services/api';

const Login: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [isCapsLockOn, setIsCapsLockOn] = useState(false);
  
  const { login } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    const checkCapsLock = (e: KeyboardEvent) => {
      setIsCapsLockOn(e.getModifierState('CapsLock'));
    };
    window.addEventListener('keydown', checkCapsLock);
    return () => window.removeEventListener('keydown', checkCapsLock);
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setIsLoading(true);

    try {
      const response = await api.post('/login', { email, password });
      await login(response.data.access_token);
      navigate('/dashboard');
    } catch (err) {
      if (axios.isAxiosError(err)) {
        setError(err.response?.data?.message || 'Invalid email or password');
      } else {
        setError('An unexpected error occurred');
      }
    } finally {
      setIsLoading(false);
    }
  };

  const handleDemoAccess = async () => {
    setIsLoading(true);
    try {
      const response = await api.post('/login-guest');
      await login(response.data.access_token);
      navigate('/dashboard');
    } catch {
      setError('Demo access currently unavailable');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <AuthLayout 
      title="Welcome back" 
      subtitle="Enter your credentials to access your account"
    >
      <form className="auth-form" onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="email">Email address</label>
          <input
            id="email"
            type="email"
            placeholder="name@example.com"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>

        <div className="form-group">
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <label htmlFor="password">Password</label>
            <Link to="/forgot-password" style={{ fontSize: '0.75rem', color: 'var(--primary-color)', textDecoration: 'none' }}>
              Forgot password?
            </Link>
          </div>
          <input
            id="password"
            type="password"
            placeholder="••••••••"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
          {isCapsLockOn && (
            <div className="caps-warning">
              <AlertCircle size={14} />
              <span>Caps Lock is ON</span>
            </div>
          )}
        </div>

        {error && <div className="error-message">{error}</div>}

        <button type="submit" className="btn" disabled={isLoading}>
          {isLoading ? 'Signing in...' : 'Sign in'}
        </button>

        <button 
          type="button" 
          className="btn" 
          style={{ backgroundColor: '#f3f4f6', color: '#4b5563', border: '1px solid #e5e7eb' }}
          onClick={handleDemoAccess}
          disabled={isLoading}
        >
          {isLoading ? 'Accessing...' : 'Demo Access (Bypass Auth)'}
        </button>
      </form>

      <div className="auth-link">
        Don't have an account? <Link to="/register">Create one for free</Link>
      </div>
    </AuthLayout>
  );
};

export default Login;
