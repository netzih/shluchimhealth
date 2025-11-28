# Amazon Associates Compliance Checklist for Shluchim Health

## ✅ COMPLETED (Already Fixed)

### Disclosure Requirements
- [x] **Footer disclosure** - Updated to exact Amazon wording: "As an Amazon Associate I earn from qualifying purchases"
- [x] **Product page disclosures** - Added "Paid link" labels and inline disclosures
- [x] **Blog post disclosures** - Added prominent disclosure box on all blog posts
- [x] **Proper link attributes** - Using `rel="nofollow noopener sponsored"` on all affiliate links

### Technical Requirements
- [x] **No link shorteners** - Using full Amazon URLs (not amzn.to or cloaked links)
- [x] **Proper affiliate tag** - Updated to `shluchimhea07-20`
- [x] **Professional design** - Clean, modern, mobile-responsive site
- [x] **Clear navigation** - Easy to browse products, blog, and legal pages

## ⚠️ ACTION REQUIRED - Verify These Before Applying

### 1. Privacy Policy (CRITICAL - REQUIRED BY AMAZON)
**Status:** Page referenced in footer, but needs verification

**Visit:** https://www.shluchimhealth.com/privacy-policy

**Must Include:**
- [ ] Data collection practices (cookies, analytics, user information)
- [ ] Third-party services disclosure (Amazon Associates, Google Analytics, etc.)
- [ ] Cookie usage and tracking
- [ ] User rights and contact information
- [ ] GDPR/CCPA compliance (if applicable)

**Amazon Requirement:** Privacy policy must be clearly accessible and comprehensive.

---

### 2. Affiliate Disclosure Page (HIGHLY RECOMMENDED)
**Status:** Page referenced in footer, needs verification

**Visit:** https://www.shluchimhealth.com/affiliate-disclosure

**Should Include:**
- [ ] Full explanation of affiliate relationship with Amazon
- [ ] Statement: "As an Amazon Associate I earn from qualifying purchases"
- [ ] Explanation that links may earn commissions at no cost to users
- [ ] List of affiliate programs you participate in
- [ ] Transparency about product recommendations

---

### 3. Content Requirements (CRITICAL)
**Amazon requires:**
- [ ] **At least 10 published blog posts** - Count your current posts
- [ ] **Most recent post within 60 days** - Check your latest publication date
- [ ] **Original, valuable content** - Not just product lists with affiliate links
- [ ] **Regular updates** - Demonstrate active site maintenance

**Action:** Run this command to count posts:
```bash
cd /home/user/shluchimhealth
php -r "require 'config.php'; require 'database.php'; echo 'Published posts: ' . db()->count('posts', 'status=\"published\"') . PHP_EOL;"
```

**Check recent posts:**
```bash
php -r "require 'config.php'; require 'database.php'; \$recent = db()->fetch('SELECT title, published_at FROM posts WHERE status=\"published\" ORDER BY published_at DESC LIMIT 1'); echo 'Latest post: ' . \$recent['title'] . ' (' . \$recent['published_at'] . ')' . PHP_EOL;"
```

---

### 4. First 180 Days Requirement
**Amazon Requirement:** New affiliates must generate **3 qualifying sales within 180 days** of approval.

**Recommendation:**
- [ ] Have a promotion strategy ready
- [ ] Share your blog posts on social media
- [ ] Build an email list for product recommendations
- [ ] Focus on high-value, in-demand health products

---

### 5. Terms of Service Page
**Status:** Referenced in footer, needs verification

**Visit:** https://www.shluchimhealth.com/terms-of-service

**Should Include:**
- [ ] Site usage terms
- [ ] Disclaimer about medical advice (critical for health site!)
- [ ] Liability limitations
- [ ] Intellectual property rights

---

## 📋 PRE-APPLICATION CHECKLIST

Before submitting your Amazon Associates application:

### Website Content
- [ ] Site has 10+ high-quality blog posts
- [ ] Latest blog post is within last 60 days
- [ ] Each post provides genuine value (not just affiliate links)
- [ ] Product reviews are detailed and helpful
- [ ] No "under construction" pages or Lorem ipsum text

### Legal Pages (All Must Exist and Be Complete)
- [ ] Privacy Policy - comprehensive and accessible
- [ ] Affiliate Disclosure - clear and prominent
- [ ] Terms of Service - complete
- [ ] About page (recommended) - builds trust

### Disclosure Compliance
- [ ] Exact Amazon wording in footer: "As an Amazon Associate I earn from qualifying purchases"
- [ ] Link-level disclosures on product pages ("Paid link" or similar)
- [ ] Blog posts have affiliate disclosure boxes
- [ ] Disclosures are prominent and easy to see

### Technical Requirements
- [ ] All Amazon links use your correct affiliate tag: `shluchimhea07-20`
- [ ] No link shorteners (amzn.to, bit.ly, etc.)
- [ ] Affiliate links have proper rel attributes: `rel="nofollow noopener sponsored"`
- [ ] Site loads properly on mobile devices
- [ ] No broken links or 404 errors

### Account Requirements
- [ ] You are 18+ years old
- [ ] Have valid Tax ID or Social Security Number
- [ ] Have bank account for payments
- [ ] Website URL is correct: https://www.shluchimhealth.com

---

## 🚨 HEALTH & WELLNESS SITE SPECIFIC WARNINGS

**CRITICAL for health sites:**

### Medical Disclaimer Required
Your site discusses health, supplements, and wellness. **You MUST have a disclaimer that:**
- [ ] States you are NOT providing medical advice
- [ ] Recommends users consult healthcare professionals
- [ ] Clarifies products are suggestions, not prescriptions
- [ ] States you are not liable for health outcomes

**Example Text to Add:**
> "The information on this website is for educational purposes only and is not intended as medical advice, diagnosis, or treatment. Always consult a qualified healthcare professional before starting any supplement regimen or making changes to your health routine. Individual results may vary. We are not responsible for any adverse effects or consequences resulting from the use of any suggestions or products mentioned on this site."

**Where to add:**
- Footer of every page (recommended)
- Dedicated "Disclaimer" or "Medical Disclaimer" page
- At the top of blog posts discussing health benefits

---

## 📊 VERIFICATION COMMANDS

Run these to check your site status:

### Count Published Blog Posts
```bash
cd ~/httpdocs
php -r "require 'config.php'; require 'database.php'; echo 'Total published posts: ' . db()->count('posts', 'status=\"published\"') . PHP_EOL;"
```

### Check Latest Post Date
```bash
php -r "require 'config.php'; require 'database.php'; \$post = db()->fetch('SELECT title, published_at FROM posts WHERE status=\"published\" ORDER BY published_at DESC LIMIT 1'); echo 'Latest: ' . \$post['title'] . ' (' . \$post['published_at'] . ')' . PHP_EOL;"
```

### Verify Affiliate Tag
```bash
php search_affiliate_tag.php shluchimhea07-20
```

### Check for Old Tag
```bash
php search_affiliate_tag.php shluchimhealt-20
```

---

## 🎯 WHEN YOU'RE READY TO APPLY

### Amazon Associates Application Process

1. **Go to:** https://affiliate-program.amazon.com/
2. **Create account** or sign in
3. **Enter your website:** https://www.shluchimhealth.com
4. **Describe your website:**
   - Category: Health & Personal Care
   - Topics: Health supplements, wellness products, holistic health
   - Type: Product reviews, informational blog

5. **Traffic sources:**
   - Organic search (SEO)
   - Direct traffic
   - Social media (if applicable)

6. **How you drive traffic:**
   - SEO-optimized blog content
   - Product reviews and comparisons
   - Social media engagement (if applicable)

7. **How you build links:**
   - Within blog content
   - Product recommendation pages
   - "Where to Buy" sections

8. **Expected monthly visitors:** Be realistic based on your analytics

### After Approval

- [ ] **Replace placeholder tag** with your approved Associate ID
- [ ] **Generate 3 sales within 180 days** or account will close
- [ ] **Check compliance monthly** - Amazon updates requirements
- [ ] **Monitor link performance** via Amazon reporting dashboard

---

## 📚 RESOURCES

### Amazon Official Documentation
- [Operating Agreement](https://affiliate-program.amazon.com/help/operating/agreement)
- [Participation Requirements](https://affiliate-program.amazon.com/help/operating/participation/)
- [Program Policies](https://affiliate-program.amazon.com/help/operating/policies)

### Key Amazon Rules
- Display "As an Amazon Associate I earn from qualifying purchases" on all pages with affiliate content
- Don't use link shorteners to hide Amazon URLs
- Don't share links in private groups (Facebook, etc.)
- Don't make medical claims about products
- Links must be clearly identifiable as affiliate/paid links

---

## ✅ FINAL CHECKLIST BEFORE SUBMITTING APPLICATION

- [ ] All legal pages exist and are complete
- [ ] 10+ published blog posts
- [ ] Latest post within 60 days
- [ ] Medical disclaimer visible
- [ ] Privacy policy comprehensive
- [ ] Affiliate disclosures on every page with links
- [ ] All affiliate links use correct tag
- [ ] No link shorteners
- [ ] Site tested on mobile
- [ ] All images load properly
- [ ] No broken links
- [ ] Professional appearance
- [ ] Original, valuable content

**If all boxes are checked, you're ready to apply! 🚀**

---

## 📞 NEED HELP?

If your application is denied again:

1. **Read the denial reason carefully** - Amazon usually specifies what's missing
2. **Common denial reasons:**
   - Incomplete privacy policy
   - Not enough content (< 10 posts)
   - Under construction pages
   - Missing affiliate disclosures
   - Site looks like spam or low-quality
   - Too commercial (all links, no value)

3. **Fix the issues** and wait 3-7 days before reapplying
4. **Don't reapply immediately** - This can flag you as spam

---

**Good luck with your Amazon Associates application!** 🎉
