import type { Consumption } from '../lib/consumption';

export function BarChart({ data, unit }: { data: Consumption[]; unit: string }) {
  if (!data.length) return <p>At least two readings are needed for a consumption.</p>;
  const max = Math.max(...data.map((d) => d.consumption), 1);
  return (
    <table>
      <tbody>
        {data.map(({ period, consumption }) => (
          <tr key={period}>
            <th>{period}</th>
            <td style={{ width: '100%' }}>
              <div
                style={{
                  background: '#1a6b3c',
                  height: '1rem',
                  width: `${(consumption / max) * 100}%`,
                }}
              />
            </td>
            <td style={{ whiteSpace: 'nowrap' }}>
              {consumption} {unit}
            </td>
          </tr>
        ))}
      </tbody>
    </table>
  );
}
