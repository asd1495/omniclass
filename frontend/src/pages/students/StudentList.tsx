import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Plus, Trash2, Mail, User as UserIcon, Loader2, AlertCircle, Pencil } from 'lucide-react';
import axios from 'axios';
import api from '../../services/api';
import './StudentList.css';

interface Student {
  id: number;
  name: string;
  email: string;
  course?: { id: number, name: string };
}

interface Course {
  id: number;
  name: string;
}

const StudentList: React.FC = () => {
  const queryClient = useQueryClient();
  const [isAdding, setIsSidebarOpen] = useState(false);
  const [editingStudent, setEditingStudent] = useState<Student | null>(null);
  const [formData, setFormData] = useState({ name: '', email: '', course_id: '' });
  const [formError, setFormError] = useState<string | null>(null);

  // Fetch Courses
  const { data: courses } = useQuery({
    queryKey: ['courses'],
    queryFn: async () => {
      const response = await api.get('/courses');
      return response.data.data as Course[];
    },
  });

  // Fetch Students
  const { data, isLoading, isError } = useQuery({
    queryKey: ['students'],
    queryFn: async () => {
      const response = await api.get('/students');
      return response.data.data as Student[];
    },
  });

  // Create Student Mutation
  const createMutation = useMutation({
    mutationFn: (newStudent: typeof formData) => api.post('/students', newStudent),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['students'] });
      setIsSidebarOpen(false);
      setFormData({ name: '', email: '', course_id: '' });
    },
    onError: (err: unknown) => {
      if (axios.isAxiosError(err)) {
        setFormError(err.response?.data?.message || 'Failed to create student');
      } else {
        setFormError('An unexpected error occurred');
      }
    }
  });

  // Update Student Mutation
  const updateMutation = useMutation({
    mutationFn: (data: typeof formData) => api.put(`/students/${editingStudent?.id}`, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['students'] });
      setEditingStudent(null);
      setIsSidebarOpen(false);
      setFormData({ name: '', email: '', course_id: '' });
    },
    onError: (err: unknown) => {
      if (axios.isAxiosError(err)) {
        setFormError(err.response?.data?.message || 'Failed to update student');
      } else {
        setFormError('An unexpected error occurred');
      }
    }
  });

  // Delete Student Mutation
  const deleteMutation = useMutation({
    mutationFn: (id: number) => api.delete(`/students/${id}`),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['students'] });
    },
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setFormError(null);
    if (editingStudent) {
      updateMutation.mutate(formData);
    } else {
      createMutation.mutate(formData);
    }
  };

  const startEdit = (student: Student) => {
    setEditingStudent(student);
    setFormData({ 
      name: student.name, 
      email: student.email, 
      course_id: student.course?.id?.toString() || '' 
    });
    setIsSidebarOpen(true);
  };

  const cancelAction = () => {
    setIsSidebarOpen(false);
    setEditingStudent(null);
    setFormData({ name: '', email: '', course_id: '' });
  };

  if (isLoading) return <div className="loading-state"><Loader2 className="spinner" /> Loading students...</div>;

  return (
    <div className="student-container">
      <div className="page-header">
        <div>
          <h1>Students</h1>
          <p>Manage your student database and records.</p>
        </div>
        <button className="btn btn-primary" onClick={isAdding ? cancelAction : () => setIsSidebarOpen(true)}>
          {isAdding ? 'Cancel' : <><Plus size={18} /> Add Student</>}
        </button>
      </div>

      {isAdding && (
        <div className="add-student-card">
          <form onSubmit={handleSubmit} className="student-form">
            <div className="form-grid">
              <div className="form-group">
                <label>Full Name</label>
                <input 
                  type="text" 
                  value={formData.name} 
                  onChange={e => setFormData({...formData, name: e.target.value})}
                  placeholder="John Doe"
                  required
                />
              </div>
              <div className="form-group">
                <label>Email Address</label>
                <input 
                  type="email" 
                  value={formData.email} 
                  onChange={e => setFormData({...formData, email: e.target.value})}
                  placeholder="john@example.com"
                  required
                />
              </div>
              <div className="form-group">
                <label>Assigned Course</label>
                <select 
                  value={formData.course_id} 
                  onChange={e => setFormData({...formData, course_id: e.target.value})}
                >
                  <option value="">Select a Course (Optional)</option>
                  {courses?.map(course => (
                    <option key={course.id} value={course.id}>{course.name}</option>
                  ))}
                </select>
              </div>
            </div>
            {formError && <div className="error-alert"><AlertCircle size={16}/> {formError}</div>}
            <div className="form-actions">
              <button type="submit" className="btn btn-primary" disabled={createMutation.isPending || updateMutation.isPending}>
                {createMutation.isPending || updateMutation.isPending ? 'Saving...' : (editingStudent ? 'Update Student' : 'Save Student')}
              </button>
            </div>
          </form>
        </div>
      )}

      {isError ? (
        <div className="error-state">Failed to load students. Please check your connection.</div>
      ) : (
        <div className="student-table-wrapper">
          <table className="student-table">
            <thead>
              <tr>
                <th>Student</th>
                <th>Email</th>
                <th>Course</th>
                <th className="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {data?.length === 0 ? (
                <tr>
                  <td colSpan={4} className="empty-row">No students found. Add your first student to get started.</td>
                </tr>
              ) : (
                data?.map(student => (
                  <tr key={student.id}>
                    <td>
                      <div className="student-info-cell">
                        <div className="avatar-small"><UserIcon size={14} /></div>
                        <span>{student.name}</span>
                      </div>
                    </td>
                    <td>
                      <div className="email-cell">
                        <Mail size={14} />
                        <span>{student.email}</span>
                      </div>
                    </td>
                    <td>
                       <span className="course-tag">{student.course?.name || 'Unassigned'}</span>
                    </td>
                    <td className="text-right">
                      <div style={{ display: 'flex', gap: '0.5rem', justifyContent: 'flex-end' }}>
                        <button className="btn-icon" onClick={() => startEdit(student)}>
                           <Pencil size={18} />
                        </button>
                        <button 
                          className="btn-icon delete" 
                          onClick={() => { if(window.confirm('Delete student?')) deleteMutation.mutate(student.id) }}
                          disabled={deleteMutation.isPending}
                        >
                          <Trash2 size={18} />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
};

export default StudentList;
