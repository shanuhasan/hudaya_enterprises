/**
 * Admin panel behaviour (vanilla JS). Everything works without JS too —
 * these are conveniences: icons, mobile sidebar, confirmations, slug, previews.
 */
import {
  createIcons, ArrowLeft, ArrowRight, Building2, Check, ChevronLeft, ChevronRight, CircleCheck, Clock,
  ExternalLink, Eye, FolderTree, ImagePlus, Images, Inbox, Layers, LayoutDashboard, LogOut, Mail, Menu,
  MessageSquare, Package, Pencil, Phone, Plus, Search, Settings, Star, Tag, Trash2, Upload, UserRound, X,
} from 'lucide';

createIcons({
  icons: {
    ArrowLeft, ArrowRight, Building2, Check, ChevronLeft, ChevronRight, CircleCheck, Clock, ExternalLink, Eye,
    FolderTree, ImagePlus, Images, Inbox, Layers, LayoutDashboard, LogOut, Mail, Menu, MessageSquare, Package,
    Pencil, Phone, Plus, Search, Settings, Star, Tag, Trash2, Upload, UserRound, X,
  },
  attrs: { 'aria-hidden': 'true' },
});

/* ---------- Mobile sidebar ---------- */
const sidebar = document.querySelector('[data-sidebar]');
const scrim = document.querySelector('[data-sidebar-scrim]');
const toggleSidebar = (open) => {
  if (!sidebar) return;
  sidebar.classList.toggle('is-open', open);
  if (scrim) scrim.hidden = !open;
  document.querySelector('[data-sidebar-open]')?.setAttribute('aria-expanded', String(open));
};
document.querySelector('[data-sidebar-open]')?.addEventListener('click', () => toggleSidebar(true));
scrim?.addEventListener('click', () => toggleSidebar(false));
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggleSidebar(false); });

/* ---------- Confirm destructive actions: <form data-confirm="Delete this product?"> ---------- */
document.addEventListener('submit', (e) => {
  const message = e.target.dataset?.confirm;
  if (message && !window.confirm(message)) e.preventDefault();
});

/* ---------- Auto-submit filters: <select data-autosubmit> ---------- */
document.querySelectorAll('[data-autosubmit]').forEach((el) => {
  el.addEventListener('change', () => el.form?.requestSubmit());
});

/* ---------- Slug from name until the slug is edited by hand ---------- */
const slugify = (s) => s.toLowerCase().replace(/['’]/g, '').normalize('NFKD').replace(/[^\w\s-]/g, '')
  .trim().replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
document.querySelectorAll('[data-slug-source]').forEach((source) => {
  const target = document.querySelector(source.dataset.slugSource);
  if (!target) return;
  let touched = target.value !== '';
  target.addEventListener('input', () => { touched = target.value !== ''; });
  source.addEventListener('input', () => { if (!touched) target.value = slugify(source.value); });
});

/* ---------- Image preview for file inputs: <input type=file data-preview="#img"> ---------- */
document.querySelectorAll('input[type=file][data-preview]').forEach((input) => {
  const img = document.querySelector(input.dataset.preview);
  input.addEventListener('change', () => {
    const file = input.files?.[0];
    if (!img || !file || !file.type.startsWith('image/')) return;
    img.src = URL.createObjectURL(file);
    img.hidden = false;
  });
});

/* ---------- Dismiss flash messages ---------- */
document.querySelectorAll('[data-dismiss]').forEach((btn) => {
  btn.addEventListener('click', () => btn.closest('[data-flash]')?.remove());
});
