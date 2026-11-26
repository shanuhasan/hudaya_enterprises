/**
 * Lucide icons — only the icons listed here are bundled.
 * Usage in HTML: <i data-lucide="arrow-right" aria-hidden="true"></i>
 * To add an icon: import its PascalCase name and add it to the object below.
 */
import {
  createIcons,
  ArrowDown,
  ArrowRight,
  ArrowUp,
  ArrowUpRight,
  Check,
  ChevronLeft,
  ChevronRight,
  CircleCheck,
  ClipboardCheck,
  Clock,
  Image,
  Layers,
  Mail,
  MapPin,
  Menu,
  MessageSquare,
  Minus,
  Phone,
  Plus,
  Ruler,
  Scissors,
  Search,
  Shirt,
  Truck,
  X,
} from 'lucide';

const icons = {
  ArrowDown,
  ArrowRight,
  ArrowUp,
  ArrowUpRight,
  Check,
  ChevronLeft,
  ChevronRight,
  CircleCheck,
  ClipboardCheck,
  Clock,
  Image,
  Layers,
  Mail,
  MapPin,
  Menu,
  MessageSquare,
  Minus,
  Phone,
  Plus,
  Ruler,
  Scissors,
  Search,
  Shirt,
  Truck,
  X,
};

export function initIcons(root = document) {
  createIcons({
    icons,
    root,
    attrs: {
      'stroke-width': 1.25,
      'aria-hidden': 'true',
      focusable: 'false',
    },
  });
}
