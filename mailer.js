const nodemailer = require ("nodemailer");
const db = require ("./db");
const transporter = nodemailer.createTransport({
     service : "gmail",
     auth : {
       user:"luu.alexandre.wong@gmail.com",
       pass: "qupd xnem gluk wcxc"
     }
});
 function sleep(ms){
return new Promise(resolve=> setTimeout(resolve, ms));
}
async function envoyerNewsletter(){
const [users]= await db.query("SELECT username,email FROM utilisateur");
const batchSize=10;
 for (let i = 0; i < users.length; i += batchSize) {
    const batch = users.slice(i, i + batchSize);

    await Promise.all(
      batch.map(user =>
        transporter.sendMail({
from: "luu.alexandre.wong@gmail.com",
to: user.email,
subject: "Newsletter - Wine Dining 🍽️",
          html: `
            <h2>Bonjour ${user.username},</h2>

            <p>
              N'hésitez pas à consulter le site pour accéder aux nouveaux plats / menus, nouvelles offres.
            </p>

            <p>
              À très bientôt.<br>
              Cordialement,<br>
              <b>Wine Dining</b>
            </p>
          `
        })
      )
    );
console.log("Batch envoyé");
await sleep(2000);
}
}
module.exports = envoyerNewsletter;

