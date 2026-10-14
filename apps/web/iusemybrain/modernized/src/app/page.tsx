import Link from 'next/link';

export default function Home() {
  return (
    <>
      <h2>Welcome to i-use-my-brain!</h2>
      <p>
        This site shows and describes special places all over Germany: sights, parks and museums.
        The places can also be fetched with our Android app.
      </p>
      <p>
        <Link href="/places">Browse places</Link> · <Link href="/places/new">Submit a place</Link> ·{' '}
        <Link href="/download">Download the app</Link>
      </p>
      <img src="/img/schnecke3.png" alt="Brain snail" width={550} />
      <p>This is the brain snail, it follows you across the whole website.</p>
    </>
  );
}
