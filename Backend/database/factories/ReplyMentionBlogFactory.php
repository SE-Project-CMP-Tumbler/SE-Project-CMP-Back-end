<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Blog;
use App\Models\Reply;

class ReplyMentionBlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $blog = Blog::factory()->create();
        $arr = [ "<p>@" . $blog->username . "</p>",
        "<p><strong>Along @" . $blog->username . "</strong> with <em>World War I</em>, World War II was one of the great watersheds of 20th-century geopolitical history. It resulted in the extension of the Soviet Union&rsquo;s power to nations of eastern&nbsp;Europe, enabled a communist movement to eventually achieve power in China, and marked the decisive shift of power in the world away from the states of western Europe and toward the United States and <span >the Soviet Union</span>.</p> <p></p> <p>&nbsp;</p> <p>Having achieved this&nbsp;cynical&nbsp;agreement, the other provisions of which stupefied Europe even without divulgence of the secret protocol, Hitler thought that Germany could attack Poland with no danger of Soviet or British intervention and gave orders for the invasion to start on August 26. News of the signing, on August 25, of a formal treaty of mutual assistance between Great Britain and Poland <em>(to supersede a previous though <span><strong>temporary</strong> </span>agreement)</em> caused him to postpone the start of hostilities for a few days. He was still determined, however, to ignore the diplomatic efforts of the western powers to restrain him. Finally, at 12:40&nbsp;<span class='text-smallcaps'>PM</span>&nbsp;on August 31, 1939, Hitler ordered hostilities against Poland to start at 4:45 the next morning. The invasion began as ordered. In response,&nbsp;<span id='ref511771'></span>Great Britain&nbsp;and&nbsp;<span id='ref511772'></span>France&nbsp;declared war on Germany on September 3, at 11:00&nbsp;<span class='text-smallcaps'>AM</span>&nbsp;and at 5:00&nbsp;<span class='text-smallcaps'>PM</span>, respectively. World War II had begun.</p> <p></p> <p>It was the qualitative superiority of the German infantry divisions and the number of their armoured divisions that made the difference in 1939. The firepower of a German infantry division far exceeded that of a French, British,</p> <p>&nbsp;</p> <p></p> <p>&nbsp;</p>",
         "<p>محمد صلاح لاعب كرة قدم مصري من مواليد مدينة بسيون عام 1992. ترعرع محمد صلاح في ظل ظروف مادية صعبة اضطرته للتخلي عن حلمه في الدراسة الجامعية، بيد أنه لم يستسلم ونجح في تحقيق نجاح ترددت أصداؤه في العالم أجمع.</p> <p></p> <p>بدأ محمد صلاح مسيرته مع نادي المقاولين سنة 2010، حيث انضم إلى صفوف فريق الناشئين. ولكن موهبته الكبيرة وتطور أدائه اللافت دفعا مدرب الفريق الأول إلى إشراكه في إحدى مباريات الدوري المصري أمام فريق المنصورة عام 2010. وفي الموسم التالي أضحى محمد صلاح لاعبًا أساسيًا في الفريق الأول، وجذب أنظار المتابعين والنقاد وأعين كشافي الأندية الأخرى. فحاول نادي الزمالك التعاقد معه، بيد أنهم اعتبروا تكلفة الصفقة كبيرة جدًا قياسًا على عمر صلاح، لذا غضوا الطرف عنها. ولكن عام 2012 حمل مفاجأةً كبيرة لصلاح، فقد قرر نادي بازل السويسري التعاقد معه، فكان ذلك تجربته الاحترافية الأولى في القارة الأوربية.</p> <p>أمضى صلاح موسمين ناجحين جدًا مع فريق بازل؛ ففي الموسم الأول نجح مع فريقه في التأهل إلى نصف نهائي الدوري الأوروبي، غير أنه لم يتمكن من الصعود إلى المباراة النهائية. ولكن رغم ذلك تألق محمد صلاح خلال أدوار البطولة، محرزًا هدفين ثمينين أمام فريقي توتنهام وتشلسي الإنجليزيين. أما على الصعيد المحلي، نجح صلاح في إحراز لقبة الدوري مع بازل، وحل وصيفًا في مسابقة الكأس بعد خسارته المباراة النهائية. @" . $blog->username . "</p>",
         "<h1><span>@" . $blog->username . "</span></h1> <p><span ><strong>Eren</strong> is best described as hardheaded, <em>strong-willed</em>, passionate, and impulsive, which are attributes of his strong determination to protect mankind and eventually escape the Walls. As a young child, he was so intent on joining the Scout Regiment that he argued with and shouted at his mother, referring to the people in the village as '<span>silly</span>' and compared them to complacent livestock.</span></p> <p><span >his Friends are:</span></p> <ol> <li><span >Armin</span></li> <li><span >Mikasa</span></li> </ol> <p></p> <p><span>As a child, Eren cared deeply for Armin, his best and only friend before he met his adoptive sister, Mikasa, and most importantly, his family, risking harm and even death in order to help them. This quality was most often demonstrated in the form of Eren taking on the larger boys who would bully Armin without hesitation; more tragically when he desperately attempted to lift the rubble crushing his mother during the Titans' assault in Shiganshina.</span></p>",
         "<div><h1>What's @Artificial intellegence? by @" . $blog->username . " </h1> <p>It's the weapon that'd end the humanity!!</p><p>#AI #humanity #freedom</p></div>",
         "<div><h1>What's Artificial @intellegence? </h1> <p>#AI #humanity #freedom</p> @" . $blog->username . "</div>",
          "<div><h1> Please work properly @" . $blog->username . "</h1></div>"
        ];
        $reply = Reply::factory()->create(['description' => $arr[rand(0, 6)]]);
        return [
            'blog_id' => $blog->id,
            'reply_id' => $reply->id
        ];
    }
}
