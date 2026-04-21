
import { useEffect, useState } from "react";

const API = "http://127.0.0.1:8000/api/admin";

export default function DriversPage() {
  const [drivers, setDrivers] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadDrivers();
  }, []);

  function loadDrivers() {
    setLoading(true);
    fetch(`${API}/drivers`)
      .then(res => res.json())
      .then(data => {
        setDrivers(data);
        setLoading(false);
      });
  }

  function toggleDriver(id) {
    if (!confirm("Ubah status driver ini?")) return;

    fetch(`${API}/drivers/${id}/toggle`, {
      method: "PATCH",
      headers: { Accept: "application/json" }
    }).then(() => loadDrivers());
  }

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Top Bar */}
      <header className="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 className="text-2xl font-bold text-gray-800">Admin Panel</h1>
        <span className="text-sm text-gray-500">Master Data Driver</span>
      </header>

      <div className="max-w-6xl mx-auto p-6">
        {/* Summary */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <SummaryCard title="Total Driver" value={drivers.length} />
          <SummaryCard title="Driver Aktif" value={drivers.filter(d => d.active).length} />
          <SummaryCard title="Driver Ready"
            value={drivers.filter(d => d.status === 'ready').length} />
        </div>

        {/* Table */}
        <div className="bg-white shadow rounded-lg overflow-hidden">
          <table className="w-full">
            <thead className="bg-gray-50">
              <tr className="text-left text-sm text-gray-600">
                <th className="p-3">Driver</th>
                <th className="p-3">Kendaraan</th>
                <th className="p-3">Status</th>
                <th className="p-3">Aktif</th>
                <th className="p-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              {loading && (
                <tr>
                  <td colSpan="5" className="text-center p-6 text-gray-500">
                    Memuat data...
                  </td>
                </tr>
              )}

              {!loading && drivers.map(d => (
                <tr key={d.id} className="border-t hover:bg-gray-50">
                  <td className="p-3">
                    <div className="font-semibold text-gray-800">{d.name}</div>
                    <div className="text-xs text-gray-500">Kode: {d.driver_code}</div>
                  </td>
                  <td className="p-3 text-gray-700">
                    {d.vehicle_category?.name ?? '-'}
                  </td>
                  <td className="p-3">
                    <StatusBadge status={d.status} />
                  </td>
                  <td className="p-3">
                    {d.active ? (
                      <span className="text-green-600 font-semibold">Aktif</span>
                    ) : (
                      <span className="text-red-600 font-semibold">Nonaktif</span>
                    )}
                  </td>
                  <td className="p-3 text-center">
                    <button
                      onClick={() => toggleDriver(d.id)}
                      className={`px-4 py-1 rounded text-sm font-medium text-white ${
                        d.active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'
                      }`}
                    >
                      {d.active ? 'Nonaktifkan' : 'Aktifkan'}
                    </button>
                  </td>
                </tr>
              ))}

              {!loading && drivers.length === 0 && (
                <tr>
                  <td colSpan="5" className="text-center p-6 text-gray-500">
                    Belum ada data driver
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

function SummaryCard({ title, value }) {
  return (
    <div className="bg-white rounded-lg shadow p-4">
      <div className="text-sm text-gray-500">{title}</div>
      <div className="text-2xl font-bold text-gray-800">{value}</div>
    </div>
  );
}

function StatusBadge({ status }) {
  const map = {
    ready: 'bg-green-100 text-green-700',
    on_trip: 'bg-yellow-100 text-yellow-700',
    break: 'bg-blue-100 text-blue-700',
    off_duty: 'bg-gray-200 text-gray-700',
    inactive: 'bg-gray-200 text-gray-500',
  };

  return (
    <span className={`px-2 py-1 rounded-full text-xs font-semibold ${map[status] ?? 'bg-gray-100'}`}>
      {status.replace('_', ' ')}
    </span>
  );
}
