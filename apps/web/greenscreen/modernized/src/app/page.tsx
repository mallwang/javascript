import { departures, news, stocks, warnings, weather } from '../lib/sources';
import { Clock, Refresh, Rotator } from './Live';

export const revalidate = 60;

export default async function Page() {
  const [dep, wx, jams, headlines, quotes] = await Promise.all([
    departures(),
    weather(),
    warnings(),
    news(),
    stocks(),
  ]);
  const ticker = quotes.map((q) => (
    <span key={q.name}>
      {q.name} {q.price.toFixed(2).replace('.', ',')}{' '}
      <b className={q.change >= 0 ? 'up' : 'down'}>
        ({q.change >= 0 ? '+' : ''}
        {q.change.toFixed(2).replace('.', ',')} %)
      </b>
    </span>
  ));
  return (
    <>
      <Refresh />
      <Clock />
      <main>
        <section>
          <Rotator seconds={10}>
            {wx.map((c) => (
              <div key={c.name}>
                <h3>{c.name}</h3>
                <p className="big">{c.temp === null ? 'n/a' : `${c.temp.toFixed(1)} °C`}</p>
                <p>{c.text}</p>
              </div>
            ))}
          </Rotator>
          {dep.map((d) => (
            <div key={d.stop}>
              <h3>{d.stop}</h3>
              <table>
                <tbody>
                  {d.rows.map((r, i) => (
                    <tr key={i}>
                      <td>{r.time}</td>
                      <td>{r.line}</td>
                      <td>{r.to}</td>
                      <td>{r.delay > 0 ? `+${r.delay}` : ''}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          ))}
        </section>
        <section>
          <h3>
            {jams.length
              ? `${jams.length} traffic warnings (A3, A6, A9, A73)`
              : 'No traffic warnings.'}
          </h3>
          <ul>
            {jams.map((w, i) => (
              <li key={i}>
                <b>{w.title}</b> {w.subtitle}
              </li>
            ))}
          </ul>
        </section>
        <section>
          <Rotator seconds={20}>
            {headlines.map((n) => (
              <div key={n.title}>
                <small>{n.topline}</small>
                <h3>{n.title}</h3>
              </div>
            ))}
          </Rotator>
        </section>
      </main>
      <footer>
        <div className="ticker">{ticker}</div>
      </footer>
    </>
  );
}
