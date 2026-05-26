import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AlertCircle } from 'lucide-react';
import AuthLayout from './AuthLayout';
import { useAuth } from '../../context/AuthContext';
import api from '../../services/api';

const Register: React.FC = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });
  const [errors, setErrors] = useState<Record<string, string[]>>({});
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

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.id]: e.target.value });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setIsLoading(true);

    try {
      const response = await api.post('/register', formData);
      await login(response.data.access_token);
      navigate('/dashboard');
    } catch (err: any) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors);
      } else {
        setErrors({ general: ['An unexpected error occurred. Please try again.'] });
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <AuthLayout 
      title="Create account" 
      subtitle="Join OmniClass to start managing your school today"
    >
      <form className="auth-form" onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="name">Full name</label>
          <input
            id="name"
            type="text"
            placeholder="Jane Doe"
            value={formData.name}
            onChange={handleChange}
            required
          />
          {errors.name && <div className="error-message">{errors.name[0]}</div>}
        </div>

        <div className="form-group">
          <label htmlFor="email">Email address</label>
          <input
            id="email"
            type="email"
            placeholder="name@example.com"
            value={formData.email}
            onChange={handleChange}
            required
          />
          {errors.email && <div className="error-message">{errors.email[0]}</div>}
        </div>

        <div className="form-group">
          <label htmlFor="password">Password</label>
          <input
            id="password"
            type="password"
            placeholder="Minimum 8 characters"
            value={formData.password}
            onChange={handleChange}
            required
          />
          {isCapsLockOn && (
            <div className="caps-warning">
              <AlertCircle size={14} />
              <span>Caps Lock is ON</span>
            </div>
          )}
          {errors.password && <div className="error-message">{errors.password[0]}</div>}
        </div>

        <div className="form-group">
          <label htmlFor="password_confirmation">Confirm password</label>
          <input
            id="password_confirmation"
            type="password"
            placeholder="Repeat your password"
            value={formData.password_confirmation}
            onChange={handleChange}
            required
          />
        </div>

        {errors.general && <div className="error-message">{errors.general[0]}</div>}

        <button type="submit" className="btn" disabled={isLoading}>
          {isLoading ? 'Creating account...' : 'Create account'}
        </button>
      </form>

      <div className="auth-link">
        Already have an account? <Link to="/login">Sign in</Link>
      </div>
    </AuthLayout>
  );
};

export default Register;
