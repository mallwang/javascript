export default function Download() {
  return (
    <>
      <h2>Download</h2>
      <p>
        <a href="/download/MyAnnoOnline.apk">
          <img src="/img/QR_Code_myannoonline.jpg" alt="QR code" width={100} /> MyAnnoOnline (APK)
        </a>
      </p>
      <p>
        <a href="/download/WoIstWas_BesondereOrte.apk">
          <img src="/img/QR_Code.png" alt="QR code" width={100} /> Wo ist was?! - Besondere Orte
          (APK)
        </a>
      </p>
      <p>
        <a href="http://play.google.com/store/apps/details?id=com.iusemybrain.apps.myannoonline">
          <img src="/img/PlayStore_Logo.png" alt="Play Store" width={100} />
        </a>
      </p>
    </>
  );
}
