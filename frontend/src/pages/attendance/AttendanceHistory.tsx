import React from 'react';
import { useQuery } from '@tanstack/react-query';
import { Calendar, CheckCircle2, Users, ArrowRight, Loader2 } from 'lucide-react';
import { Link } from 'react-router-dom';
import api from '../../services/api';
import './AttendanceTracker.css';

interface HistoryRecord {
  date: string;
  present: int;
  total: int;
}

const AttendanceHistory: React.FC = () => {
  const { data, isLoading } = useQuery({
    queryKey: ['attendance-history'],
    queryFn: async () => {
      const response = await api.get('/attendance/history');
      return response.data.data as HistoryRecord[];
    },
  });

  if (isLoading) return <div className="loading-state"><Loader2 className="spinner" /> Loading history...</div>;

  return (
    <div className="attendance-container">
      <div className="page-header">
        <div>
          <h1>Attendance History</h1>
          <p>Chronological summary of past attendance records.</p>
        </div>
      </div>

      <div className="attendance-grid">
        <div className="attendance-header-row">
          <span>Date</span>
          <span>Stats</span>
          <span className="text-right">Action</span>
        </div>

        <div className="attendance-body">
          {data?.length === 0 ? (
            <div className="empty-row">No attendance history found. Start recording daily attendance to see trends.</div>
          ) : (
            data?.map(record => {
              const rate = ((record.present / record.total) * 100).toFixed(1);
              return (
                <div key={record.date} className="attendance-row">
                  <div className="student-profile">
                    <Calendar size={18} className="text-muted" style={{ marginRight: '0.5rem' }} />
                    <span style={{ fontWeight: 600 }}>{record.date}</span>
                  </div>

                  <div style={{ display: 'flex', gap: '1.5rem', fontSize: '0.875rem' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.375rem' }}>
                       <CheckCircle2 size={16} color="#22c55e" />
                       <span>{record.present} Present</span>
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.375rem' }}>
                       <Users size={16} color="#6b7280" />
                       <span>{record.total} Total</span>
                    </div>
                    <div style={{ fontWeight: 700, color: Number(rate) > 80 ? '#166534' : '#92400e' }}>
                       {rate}%
                    </div>
                  </div>

                  <div className="text-right">
                    <Link to={`/attendance?date=${record.date}`} className="btn-icon" title="View Details">
                      <ArrowRight size={20} />
                    </Link>
                  </div>
                </div>
              );
            })
          )}
        </div>
      </div>
    </div>
  );
};

export default AttendanceHistory;
