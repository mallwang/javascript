export default function Impressum() {
  return (
    <>
      <h2>Imprint</h2>
      <p>
        <b>i-use-my-brain.com</b>
        <br />
        {process.env.IMPRINT_NAME}
      </p>
    </>
  );
}
